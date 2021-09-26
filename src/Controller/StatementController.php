<?php

namespace App\Controller;

use App\Entity\CouncilSession;
use App\Entity\DevelopmentSolution;
use App\Form\ApplicationSessionType;
use App\Form\DevelopmentSolutionFormType;
use App\Repository\CouncilSessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\DevelopmentApplication;
use App\Form\DevelopmentApplicationType;
use App\Repository\DevelopmentApplicationRepository;
use App\Repository\DevelopmentSolutionRepository;
use Symfony\Component\Workflow\WorkflowInterface;
use Symfony\Component\Workflow\Exception\LogicException;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class StatementController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(): Response
    {
        return $this->render('statement/index.html.twig');
    }


    /**
     * @Route("/appl", name="statement.list")
     */
    public function list(DevelopmentApplicationRepository $developmentApplicationRepository): Response
    {
        $developmentApplications = $developmentApplicationRepository->findAll();

        return $this->render('statement/list.html.twig', [
            'developmentApplications' => $developmentApplications,
        ]);
    }

    /**
     * @Route("/sess", name="statement.sessions")
     */
    public function sessions(CouncilSessionRepository $repository): Response
    {
        $sessions = $repository->findAll();

        return $this->render('statement/session_list.html.twig', [
            'sessions' => $sessions,
        ]);
    }

    /**
     * @Route("/sol", name="statement.solutions")
     */
    public function solutions(DevelopmentSolutionRepository $repository): Response
    {
        $solutions = $repository->findAll();

        return $this->render('statement/solutions_list.html.twig', [
            'solutions' => $solutions,
        ]);
    }

    /**
     * @Route("/sess/{id}", name="statement.session")
     */
    public function session(CouncilSession $session) {
        return $this->render('statement/list.html.twig',[
            'developmentApplications'=>$session->getDevelopmentApplications()]);
    }

    /**
     * @Route("/new", name="statement.new")
     */
    public function new(Request $request, WorkflowInterface $applicationFlowStateMachine, EntityManagerInterface $entityManager): Response
    {
        $developmentApplication = new DevelopmentApplication();
        $form = $this->createForm(DevelopmentApplicationType::class, $developmentApplication, [
            'entity_manager' => $entityManager,
        ])->add('save', SubmitType::class
        //['validate'=>false]
        );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $applicationFlowStateMachine->getMarking($developmentApplication);
            $em = $this->getDoctrine()->getManager();
            $em->persist($developmentApplication);
            $em->flush();
            return $this->render('statement/added.html.twig');
        }

        return $this->render('statement/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/statement/add_solution/{id}", name="statement.add_solution")
     */
    public function addSolution(WorkflowInterface $applicationFlowStateMachine, DevelopmentApplication $developmentApplication, Request $request): Response
    {
        $developmentSolution = null === $developmentApplication->getSolution() ? new DevelopmentSolution() : $developmentApplication->getSolution();
        $form = $this->createForm(DevelopmentSolutionFormType::class, $developmentSolution)->add('save', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $applicationFlowStateMachine->apply($developmentApplication, "to_number");
                $developmentSolution->setDevelopmentApplication($developmentApplication);
                $em = $this->getDoctrine()->getManager();
                $em->persist($developmentApplication);
                $em->persist($developmentSolution);
                $em->flush();
            } catch (LogicException $exception) {
                return $this->render('statement/connect_session.twig', [
                    'developmentApplication' => $developmentApplication,
                    'exception' => $exception,
                    'form' => $form->createView(),
                ]);
            }
            return $this->redirectToRoute('statement.list');
        }
        return $this->render('statement/connect_session.twig', [
            'developmentApplication' => $developmentApplication,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/appl/{id}", name="statement.update")
     */
    public function update(WorkflowInterface $applicationFlowStateMachine, DevelopmentApplication $developmentApplication, EntityManagerInterface $entityManager, Request $request): Response
    {
        // $applicationFlowStateMachine->apply($developmentApplication, "reopen");
        $session_dates = [];
        foreach($entityManager->getRepository(CouncilSession::class)->findAllDates() as $session_date) {
            $session_dates[]=$session_date['isAt']->format('Y-m-d');
        }
        if ($applicationFlowStateMachine->can($developmentApplication, 'to_number')) {
            $form = $this->createFormBuilder($developmentApplication)
                ->add('appealNumber', TextType::class, ['label' => 'Присвоїти номер'])
                ->add('councilSession', ApplicationSessionType::class, ['label' => false])
                ->add('save', SubmitType::class)
                ->getForm();
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                try {
                    $session = $entityManager->getRepository(CouncilSession::class)->findByDate($developmentApplication->getCouncilSession()->getIsAt());
                    if ($session) {
                        $developmentApplication->setCouncilSession($session);
                    } else {
                        $session = $developmentApplication->getCouncilSession();
                    }

                    $applicationFlowStateMachine->apply($developmentApplication, "to_number");
                    $entityManager->persist($session);
                    $entityManager->persist($developmentApplication);
                    $entityManager->persist($developmentApplication);
                    $entityManager->flush();
                    return $this->redirectToRoute('statement.list');
                } catch (LogicException $exception) {
                    dump($exception);
                }
            }
            return $this->render('statement/connect_session.twig', [
                'developmentApplication' => $developmentApplication,
                'form' => $form->createView(),
                'sessionDates' => implode($session_dates,',')
            ]);
        }

        if ($applicationFlowStateMachine->can($developmentApplication, 'publish')) {
            $developmentSolution = new DevelopmentSolution();
            $form = $this->createForm(DevelopmentSolutionFormType::class, $developmentSolution)->add('save', SubmitType::class);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                try {
                    if ($developmentSolution->getAction()) {
                        $applicationFlowStateMachine->apply($developmentApplication, "reject");
                    } else {
                        $applicationFlowStateMachine->apply($developmentApplication, "publish");
                    }
                    $developmentSolution->setDevelopmentApplication($developmentApplication);
                    $entityManager->persist($developmentApplication);
                    $entityManager->persist($developmentSolution);
                    $entityManager->flush();
                } catch (LogicException $exception) {
                    return $this->render('statement/added.html.twig', [
                        'developmentApplication' => $developmentApplication,
                        'exception' => $exception,
                        'form' => $form->createView(),
                    ]);
                }
                return $this->redirectToRoute('statement.list');
            }
            return $this->render('statement/solution.html.twig', [
                'developmentApplication' => $developmentApplication,
                'form' => $form->createView(),
            ]);
        }
        return $this->render('statement/solution_view.html.twig', [
            'developmentApplication' => $developmentApplication
        ]);
    }

    /**
     * @Route("/history/{id}", name="statement.history")
     */
    public function history(DevelopmentSolution $developmentSolution): Response
    {
        return $this->render('statement/solution_view.html.twig', [
            'developmentApplication' => $developmentSolution->getDevelopmentApplication()
        ]);
    }

    /**
     * @Route("/calendar", name="statement.calendar")
     */
    public function calendar(): Response
    {
        return $this->render('statement/calendar.html.twig');
    }
}
