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

class DrawenAreaController extends AbstractController
{
    /**
     * @Route("/dr_map", name="drawen.draw_map")
     */
    public function drawMap(Request $request): Response
    {
        $form = $this->createForm(DrawnAreaType::class, new DrawnArea());

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
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        try {
            $drawnArea = new DrawnArea();
            $form = $this->createForm(DrawnAreaType::class, $drawnArea);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($drawnArea);
                $em->flush();
                return new JsonResponse(['success' => true]);
            }
        } catch (\Exception $exception) {
            return $this->json(['error' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
