<?php

namespace App\Controller;

use App\Entity\DevelopmentSolution;
use App\Form\DevelopmentSolutionFormType;
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

class StatementController extends AbstractController
{
    /**
     * @Route("/statement", name="statement.list")
     */
    public function index(DevelopmentApplicationRepository $developmentApplicationRepository): Response
    {
        $developmentApplications = $developmentApplicationRepository->findAll();

        return $this->render('statement/list.html.twig', [
            'developmentApplications' => $developmentApplications,
        ]);
    }

    /**
     * @Route("/statement/new", name="statement.new")
     */
    public function new(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $developmentApplication = new DevelopmentApplication();
        $form = $this->createForm(DevelopmentApplicationType::class, $developmentApplication, [
            'entity_manager' => $entityManager,
        ])->add('save', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($developmentApplication);
            $em->flush();
            return $this->redirectToRoute('statement.list');
        }
        return $this->render('statement/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/statement/add_number/{id}", name="statement.add_number")
     */
    public function addNumber(WorkflowInterface $applicationFlowStateMachine, DevelopmentApplication $developmentApplication, Request $request): Response {
        $developmentSolution = null === $developmentApplication->getSolution() ? new DevelopmentSolution() : $developmentApplication->getSolution();
        $form = $this->createForm(DevelopmentSolutionFormType::class, $developmentSolution)->add('save', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $applicationFlowStateMachine->apply($developmentApplication,"to_number");
                $developmentSolution->setDevelopmentApplication($developmentApplication);
                $em = $this->getDoctrine()->getManager();
                $em->persist($developmentApplication);
                $em->persist($developmentSolution);
                $em->flush();
            } catch (LogicException $exception) {
                return $this->render('statement/add_number.html.twig', [
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
}
