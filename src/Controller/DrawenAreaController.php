<?php

namespace App\Controller;

use App\Entity\DrawnArea;
use App\Form\DrawnAreaType;
use App\Repository\DrawnAreaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Workflow\WorkflowInterface;
use DateTimeImmutable;

class DrawenAreaController extends AbstractController
{
    /**
     * @Route("/dr_map", name="drawen.draw_map")
     */
    public function drawMap(Request $request): Response
    {
        $form = $this->createForm(DrawnAreaType::class, new DrawnArea(),['action'=>$this->generateUrl('drawen.draw_add')]);

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
    public function add(Request $request, WorkflowInterface $drawnAreaFlowStateMachine, EntityManagerInterface $em): Response
    {
        try {
            $drawnArea = new DrawnArea();
            $form = $this->createForm(DrawnAreaType::class, $drawnArea,[
                'entity_manager' => $this->getDoctrine()->getManager(),
                'action'=>$this->generateUrl('drawen.draw_add')
            ]);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $drawnAreaFlowStateMachine->getMarking($drawnArea);
                $em->persist($drawnArea);
                $em->flush();
                return new JsonResponse(['success' => true]);
            }
            $content = $this->renderView(
                'statement/modals/draw_toast_wo_div.html.twig',
                array('form' => $form->createView(), 'drawnArea'=>$drawnArea)
            );
            return new JsonResponse(['content'=> $content]);
        } catch (Exception $exception) {
            return $this->json(['error' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/dr_upd/{id}", name="drawen.draw_upd")
     */
    public function upd(Request $request, EntityManagerInterface $em, DrawnArea $drawnArea): Response
    {
        try {
            $form = $this->createForm(DrawnAreaType::class, $drawnArea,[
                'entity_manager' => $this->getDoctrine()->getManager(),
                'action'=>$this->generateUrl('drawen.draw_upd',['id'=>$drawnArea->getId()])
            ]);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->addFlash(
                    'success',
                    ['Виправлену інформацію внесено', date("d-m-Y H:i:s")]
                );
                $em->persist($drawnArea);
                $em->flush();
                return new JsonResponse(['success' => true]);
            }
            $content = $this->renderView(
                'statement/modals/draw_toast_wo_div.html.twig',
                array('form' => $form->createView(), 'drawnArea' => $drawnArea)
            );
            return new JsonResponse(['content'=> $content]);
        } catch (Exception $exception) {
            return $this->json(['error' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/dr_publ/{drawnArea}", name="drawen.draw_publ", methods={"GET"}, options={"expose"=true})
     */
    public function publ(DrawnArea $drawnArea, EntityManagerInterface $em, WorkflowInterface $drawnAreaFlowStateMachine): Response
    {
        try {
                $this->addFlash(
                    'success',
                    ['Виправлену інформацію внесено', date("d-m-Y H:i:s")]
                );
                $drawnAreaFlowStateMachine->apply($drawnArea, 'to_publish');
                $drawnArea->setPublishedAt(new DateTimeImmutable('now'));
                $em->persist($drawnArea);
                $em->flush();
                return new JsonResponse(['success' => true]);
        } catch (Exception $exception) {
            return $this->json(['error' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * @Route("/dr_drop/{drawnArea}", name="drawen.draw_drop", methods={"GET"}, options={"expose"=true})
     */
    public function drop(DrawnArea $drawnArea, EntityManagerInterface $em): Response
    {
        try {
            $this->addFlash(
                'success',
                ['Виправлену інформацію внесено', date("d-m-Y H:i:s")]
            );
            $em->remove($drawnArea);
            $em->flush();
            return new JsonResponse(['success' => true]);
        } catch (Exception $exception) {
            return $this->json(['error' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * @Route("/drawen_geoms", name="drawen.all_geoms")
     */
    public
    function allGeoms(DrawnAreaRepository $repository, SerializerInterface $serializer): Response
    {
        try {
            $geoms = $serializer->serialize($repository->findAll(), 'json', ['groups' => 'geoms']);
            return new Response($geoms);//$this->json($geoms, Response::HTTP_OK);
        } catch (Exception $exception) {
            return $this->json(['error' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
