<?php

namespace App\Controller;

use App\Entity\ArchiveGround;
use App\Entity\ArchiveGroundGov;
use App\Entity\DrawnArea;
use App\Entity\Profile;
use App\Form\ArchiveGroundType;
use App\Form\ArchiveGroundGovType;
use App\Form\DrawnAreaType;
use App\Form\ProfileTypeOLD;
use App\Repository\DrawnAreaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;

use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Workflow\WorkflowInterface;
use DateTimeImmutable;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpKernel\Exception\HttpException;

use Symfony\Component\Form\FormInterface;

class DrawenAreaController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     * @Route("/dr_map", name="drawen.draw_map")
     */
    public function drawMap(Request $request, FlashBagInterface $flashBag): Response
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
        $instruct = 0;
        if(!$flashBag->has('instruct_message')) {
            $flashBag->add('instruct_message', 'true');
            $instruct = 1;
        }
        return $this->render('statement/draw_map.html.twig', ['form' => $form->createView(),
            'cc' => $cc, 'z' => $z, 'instruct'=>$instruct]);
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
              //  $form = $this->createForm(ProfileTypeOLD::class, $profile);
                $form = $this->createFormBuilder($profile)
                    ->add('url',null,['label'=>'Посилання на сайт'])
                    ->getForm()
                ;
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    $user->setProfile($profile);
                    //  $profile->setUsers($user);
                    $em->persist($profile);
                    $em->flush();
                    return new JsonResponse(['success' => true]);
                }

                return new JsonResponse(['content' => $this->render('statement/modals/swal_person.html.twig',
                    ['profile'=>$profile,'profileForm' => $form->createView()])->getContent()]);
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
            if ($form->isSubmitted()) {
                if (!$form->isValid()) {
                    throw new HttpException(412, $this->getErrorsFromForm($form)[0]);
                }
                $drawnAreaFlowStateMachine->getMarking($drawnArea);
                $drawnArea->setAuthor($this->getUser());
                $em->persist($drawnArea);
                $em->flush();
                return new JsonResponse(['success' => true, 'id' => $drawnArea->getId(),
                    'appl' => '<div>'.$drawnArea->getNumberSolution(). '</div><div>' .$drawnArea->getSolutedAt()->format('d-m-Y').'</div><div>'.
                    round(($drawnArea->getArea() / 10000) * 100) / 100 . ' Га</div>',
                    'published' => $drawnArea->getPublishedAt()
                ]);
            }
            $profile = $this->getUser()->getProfile();
            if ($profile) {
                $form->get('address')->setData($profile->getAddress());
                $form->get('link')->setData($profile->getUrl());
            }
            $content = $this->renderView(
                'statement/modals/swal_area.html.twig',
                array('form' => $form->createView(), 'drawnArea' => $drawnArea)
            );
            $buttons = $this->renderView(
                'statement/modals/swal_area_buttons.html.twig', ['drawnArea' => $drawnArea]);
            return new JsonResponse(['content' => $content, 'buttons' => $buttons]);
        } catch (HttpException $exception) {
            return $this->json(['error' => $exception->getMessage()], $exception->getStatusCode());
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
                throw new AccessDeniedException('Немає доступу до об\'єкту', Response::HTTP_NOT_ACCEPTABLE);
            }
            if($drawnArea->getStatus()!=='created') {
                $content = $this->renderView(
                    'statement/modals/swal_area_info.html.twig',
                    ['drawnArea' => $drawnArea]
                );
                $buttons = $this->renderView(
                    'statement/modals/swal_area_buttons.html.twig', ['drawnArea' => $drawnArea]);
                return new JsonResponse(['content' => $content, 'buttons' => $buttons]);
            }
            $form = $this->createForm(DrawnAreaType::class, $drawnArea, [
                'entity_manager' => $em,
                'action' => $this->generateUrl('drawen.draw_upd', ['id' => $drawnArea->getId()]),
            ]);
            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                if (!$form->isValid()) {
                    throw  new Exception($this->getErrorsFromForm($form)[0], 412);
                   // throw new HttpException(412, $this->getErrorsFromForm($form)[0]);
                }
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
            $buttons = $this->renderView(
                'statement/modals/swal_area_buttons.html.twig', ['drawnArea' => $drawnArea]);
            return new JsonResponse(['content' => $content, 'buttons' => $buttons]);
        }
        catch (Exception $exception) {
            return $this->json(['error' => $exception->getMessage()], $exception->getCode());
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
//        $h = $request->headers->all();
//        if (array_key_exists('content-type',$h)&&(("application/json" !== $h['content-type'][0]))) {
//
//            return new JsonResponse(['error' => "Ви повинні увійти, щоб отримати доступ"], Response::HTTP_NOT_ACCEPTABLE);
//        }
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
    public function archGroundForm(DrawnArea $drawnArea, Request $request, EntityManagerInterface $em, WorkflowInterface $drawnAreaFlowStateMachine): JsonResponse
    {
        try {
            $archiveGround = new ArchiveGround();
            $archiveGround->setDrawnArea($drawnArea);
            $form = $this->createForm(ArchiveGroundType::class, $archiveGround);
            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                if (!$form->isValid()) {
                    throw  new Exception("Заповнюйте поля точно", 412);
                }
                $drawnAreaFlowStateMachine->apply($drawnArea, 'to_archive');
                $drawnArea->setArchivedAt(new DateTimeImmutable('now'));
                $em->persist($drawnArea);
                $em->persist($archiveGround);
                $em->flush();
                return new JsonResponse(['success' => true]);
            }

            $archiveGroundGov = new ArchiveGroundGov();
            $archiveGroundGov->setDrawnArea($drawnArea);
            $formGov = $this->createForm(ArchiveGroundGovType::class, $archiveGroundGov);
            $formGov->handleRequest($request);
            if ($formGov->isSubmitted()) {
                if (!$formGov->isValid()) {
                    throw  new Exception("Заповнюйте поля точно", 412);
                    // throw new HttpException(412, $this->getErrorsFromForm($form)[0]);
                }
                $drawnAreaFlowStateMachine->apply($drawnArea, 'to_archive');
                $drawnArea->setArchivedAt(new DateTimeImmutable('now'));
                $em->persist($drawnArea);
                $em->persist($archiveGroundGov);
                $em->flush();
                return new JsonResponse(['success' => true]);
            }
            $content = $this->renderView(
                'statement/modals/arch_ground_form.html.twig',
                [
                    'form' => $form->createView(),
                    'formGov' => $formGov->createView()
                ]);
            return new JsonResponse(['content' => $content]);
        } catch (Exception $exception) {
            return $this->json(['error' => $exception->getMessage()], 412);
        }
    }

    private function getErrorsFromForm(FormInterface $form)
    {
        $errors = array();
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getErrorsFromForm($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }
        return $errors;
    }
}
