<?php

namespace App\Controller;

use App\Entity\ArchiveGround;
use App\Entity\DrawnArea;
use App\Entity\Profile;
use App\Form\ArchiveGroundType;
use App\Form\DrawnAreaType;
use App\Form\ProfileType;
use App\Repository\DrawnAreaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Workflow\WorkflowInterface;
use DateTimeImmutable;
use Symfony\Component\Security\Core\User\UserInterface;

class DrawenAreaController extends AbstractController
{
    /**
     * @Route("/dr_map", name="drawen.draw_map")
     */
    public function drawMap(Request $request): Response
    {
        $form = $this->createForm(DrawnAreaType::class, new DrawnArea(), ['action' => $this->generateUrl('drawen.draw_add')]);
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
        return $this->render('statement/draw_map.html.twig', ['form' => $form->createView(),
            'cc' => $cc, 'z' => $z]);
    }

    /**
     * @Route("/dr_profile", name="drawen.draw_profile")
     */
    public function profile(Request $request, EntityManagerInterface $em, TokenStorageInterface $tokenStorage): Response
    {
        {
            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

            try {
                $user = $tokenStorage->getToken()->getUser();
                $cond = (array)$user->getProfile();
                $profile = $cond ? $user->getProfile() : new Profile();
                $form = $this->createForm(ProfileType::class, $profile);
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    $user->setProfile($profile);
                    //  $profile->setUsers($user);
                    $em->persist($profile);
                    $em->flush();
                    return new JsonResponse(['success' => true]);
                }

                return new JsonResponse(['content' => $this->render('statement/modals/swal_person.html.twig', ['profileForm' => $form->createView()])->getContent()]);
            } catch (Exception $exception) {
                return $this->json(['error' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }

    /**
     * @Route("/dr_add", name="drawen.draw_add")
     */
    public function add(Request $request, WorkflowInterface $drawnAreaFlowStateMachine, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        try {
            $drawnArea = new DrawnArea();
            $form = $this->createForm(DrawnAreaType::class, $drawnArea, [
                'entity_manager' => $this->getDoctrine()->getManager(),
                'action' => $this->generateUrl('drawen.draw_add')
            ]);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $drawnAreaFlowStateMachine->getMarking($drawnArea);
                $drawnArea->setAuthor($this->getUser());
                $em->persist($drawnArea);
                $em->flush();
                return new JsonResponse(['success' => true, 'id' => $drawnArea->getId()]);
            }
            $profile = $this->getUser()->getProfile();
            if ($profile) {
                $form->get('firstname')->setData($profile->getFirstname());
                $form->get('lastname')->setData($profile->getLastname());
                $form->get('middlename')->setData($profile->getMiddlename());
                $form->get('localGoverment')->setData($profile->getLocalGoverment());
                $form->get('address')->setData($profile->getAddress());
                $form->get('link')->setData($profile->getUrl());
            }
            $content = $this->renderView(
                'statement/modals/draw_toast_wo_div.html.twig',
                array('form' => $form->createView(), 'drawnArea' => $drawnArea)
            );
            return new JsonResponse(['content' => $content]);
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
            if ($drawnArea->getAuthor() !== $this->getUser()) {
                throw new AccessDeniedException('Немає доступу до об\'єкту');
            }
            $form = $this->createForm(DrawnAreaType::class, $drawnArea, [
                'entity_manager' => $this->getDoctrine()->getManager(),
                'action' => $this->generateUrl('drawen.draw_upd', ['id' => $drawnArea->getId()])
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
                'statement/modals/swal_area.html.twig',
                array('form' => $form->createView(), 'drawnArea' => $drawnArea)
            );
            return new JsonResponse(['content' => $content]);
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
            if ($drawnArea->getAuthor() !== $this->getUser()) {
                throw new AccessDeniedException('Немає доступу до об\'єкту');
            }
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
     * @Route("/dr_arch/{drawnArea}", name="drawen.draw_arch", methods={"GET"}, options={"expose"=true})
     */
    public function arch(DrawnArea $drawnArea, EntityManagerInterface $em, WorkflowInterface $drawnAreaFlowStateMachine): Response
    {
        try {
            if ($drawnArea->getAuthor() !== $this->getUser()) {
                throw new AccessDeniedException('Немає доступу до об\'єкту');
            }
            $this->addFlash(
                'success',
                ['Виправлену інформацію внесено', date("d-m-Y H:i:s")]
            );
            $drawnAreaFlowStateMachine->apply($drawnArea, 'to_archive');
            $drawnArea->setArchivedAt(new DateTimeImmutable('now'));
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
            if ($drawnArea->getAuthor() !== $this->getUser()) {
                throw new AccessDeniedException('Немає доступу до об\'єкту');
            }
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
     * @Route("/drawen_geoms", name="drawen.all_geoms", methods={"GET"})
     */

    // , condition="request.isXmlHttpRequest()"
    public
    function allGeoms(DrawnAreaRepository $repository, SerializerInterface $serializer, Request $request): Response
    {
        try {
            $geoms = $serializer->serialize($repository->findBy(['author' => $this->getUser()]), 'json', ['groups' => 'geoms']);
            return new Response($geoms);//$this->json($geoms, Response::HTTP_OK);
        } catch (Exception $exception) {
            return $this->json(['error' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/dr_archground/{drawnArea}", name="drawen.arch.ground", methods={"GET", "POST"}, options={"expose"=true})
     */
    public function archGroundForm(DrawnArea $drawnArea, Request $request, EntityManagerInterface $em): JsonResponse
    {
        try {
            $archiveGround = new ArchiveGround();
            $archiveGround->setDrawnArea($drawnArea);
            $form = $this->createForm(ArchiveGroundType::class, $archiveGround);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($archiveGround);
                $em->flush();
                return new JsonResponse(['yes'=>'ok']);
            }
            $content = $this->renderView(
                'statement/modals/arch_ground_form.html.twig',
                ['form' => $form->createView()]);
            return new JsonResponse(['content' => $content]);
        } catch (Exception $exception) {
            return $this->json(['error' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
