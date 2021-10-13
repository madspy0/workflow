<?php

namespace App\Controller;

use App\Entity\DrawnArea;
use App\Form\DrawnAreaType;
use App\Repository\DrawnAreaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Workflow\WorkflowInterface;
use Symfony\Component\Serializer\SerializerInterface;

class DrawenAreaController extends AbstractController
{
    /**
     * @Route("/dr_map", name="drawen.draw_map")
     */
    public function drawMap(Request $request): Response
    {
        $form = $this->createForm(DrawnAreaType::class, new DrawnArea(),['action' => $this->generateUrl('drawen.draw_add'),]);
        $cc = $request->query->get('cc');
        $temp = explode(',', $cc);
        if (count($temp) == 2) {
            $z = $request->query->get('z');
            if (!(is_float($temp[0] + 0) && is_float($temp[1] + 0) && is_float($z + 0))) {
                $cc = null;
                $z = null;
            }
        } else {
            $cc = null;
            $z = null;
        }
        return $this->render('statement/draw_map.html.twig', ['form' => $form->createView(), 'cc' => $cc, 'z' => $z]);
    }

    /**
     * @Route("/dr_add", name="drawen.draw_add")
     */
    public function add(Request $request, EntityManagerInterface $em, WorkflowInterface $applicationFlowStateMachine): Response
    {
        try {
            $drawnArea = new DrawnArea();
            $form = $this->createForm(DrawnAreaType::class, $drawnArea);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $applicationFlowStateMachine->getMarking($drawnArea);
                $em->persist($drawnArea);
                $em->flush();
                $this->addFlash(
                    'success',
                    ['Дiлянка додана', date("d-m-Y H:i:s")]
                );
                return $this->redirectToRoute('drawen.draw_map');
            }
            return new JsonResponse(['errors'=>count($form->getErrors())]);
        } catch (\Exception $exception) {
            return $this->json(['error' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * @Route("/drawen_geoms", name="drawen.all_geoms")
     */
    public function allGeoms(DrawnAreaRepository $repository, SerializerInterface $serializer): Response
    {
        try {
            $geoms = $serializer->serialize($repository->findAll(), 'json', ['groups' => 'geoms']);
            return new Response($geoms);//$this->json($geoms, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return $this->json(['error' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
