<?php

namespace App\Controller;

use App\Entity\DevelopmentSolution;
use App\Form\DevelopmentSolutionFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\DevelopmentApplication;
use App\Form\DevelopmentApplicationType;
use App\Repository\DevelopmentApplicationRepository;
use Symfony\Component\Workflow\WorkflowInterface;
use Symfony\Component\Workflow\Exception\LogicException;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class StatementController extends AbstractController
{
    /**
     * @Route("/list", name="statement.list")
     */
    public function list(DevelopmentApplicationRepository $developmentApplicationRepository): Response
    {
        $developmentApplications = $developmentApplicationRepository->findAll();

        return $this->render('statement/list.html.twig', [
            'developmentApplications' => $developmentApplications,
        ]);
    }

    /**
     * @Route("/", name="statement.new")
     */
    public function new(Request $request, WorkflowInterface $applicationFlowStateMachine): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $developmentApplication = new DevelopmentApplication();
        $form = $this->createForm(DevelopmentApplicationType::class, $developmentApplication, [
            'entity_manager' => $entityManager,
        ])->add('save', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $applicationFlowStateMachine->getMarking($developmentApplication);
            $em = $this->getDoctrine()->getManager();
            $em->persist($developmentApplication);
            $em->flush();
            //           return $this->redirectToRoute('statement.list');
            return $this->render('statement/added.html.twig');
        }
        return $this->render('statement/new.html.twig', [
            'form' => $form->createView(),
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
                return $this->render('add_solution.html.twig', [
                    'developmentApplication' => $developmentApplication,
                    'exception' => $exception,
                    'form' => $form->createView(),
                ]);
            }
            return $this->redirectToRoute('statement.list');
        }
        return $this->render('add_solution.html.twig', [
            'developmentApplication' => $developmentApplication,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/update/{id}", name="statement.update")
     */
    public function update(WorkflowInterface $applicationFlowStateMachine, DevelopmentApplication $developmentApplication, EntityManagerInterface $entityManager, Request $request): Response
    {
        // $applicationFlowStateMachine->apply($developmentApplication, "reopen");
        if ($applicationFlowStateMachine->can($developmentApplication, 'to_number')) {
            {
                $form = $this->createFormBuilder($developmentApplication)
                    ->add('appealNumber', TextType::class,['label'=>'Присвоїти номер'])
                    ->add('save', SubmitType::class)
                    ->getForm();
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    try {
                        $applicationFlowStateMachine->apply($developmentApplication, "to_number");
                        $entityManager->persist($developmentApplication);
                        $entityManager->flush();
                        return $this->redirectToRoute('statement.list');
                    } catch (LogicException $exception) {
                        dump($exception);
                    }
                }
                return $this->render('statement/add_number.html.twig', [
                    'developmentApplication' => $developmentApplication,
                    'form' => $form->createView(),
                ]);
            }
        }

        if ($applicationFlowStateMachine->can($developmentApplication, 'publish')) {
            $developmentSolution  = new DevelopmentSolution();
            $form = $this->createForm(DevelopmentSolutionFormType::class, $developmentSolution)->add('save', SubmitType::class);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                try {
                    if($developmentSolution->getAction()) {
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
            return $this->render('statement/add_number.html.twig', [
                'developmentApplication' => $developmentApplication,
                'form' => $form->createView(),
            ]);
        }
        return $this->render('statement/history.html.twig', [
            'solution' => $developmentApplication->getSolution()->last()
        ]);
    }
    /**
     * @Route("/history/{id}", name="statement.history")
     */
    public function history(DevelopmentSolution $developmentSolution): Response
    {
        return $this->render('statement/history.html.twig', [
            'solution' => $developmentSolution
        ]);
    }
}
