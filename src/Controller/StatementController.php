<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\DevelopmentApplication;
use App\Form\DevelopmentApplicationType;
use App\Repository\DevelopmentApplicationRepository;

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
    public function new(): Response
    {
        // creates a task object and initializes some data for this example
        $developmentApplication = new DevelopmentApplication();

        $form = $this->createForm(DevelopmentApplicationType::class, $developmentApplication);

        return $this->render('statement/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
