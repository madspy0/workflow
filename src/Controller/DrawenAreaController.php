<?php

namespace App\Controller;

use App\Entity\ArchiveGround;
use App\Entity\ArchiveGroundGov;
use App\Entity\DrawnArea;
use App\Entity\DzkAdminObl;
use App\Entity\Profile;
use App\Form\ArchiveGroundType;
use App\Form\ArchiveGroundGovType;
use App\Form\DrawnAreaType;
use App\Form\ProfileTypeOLD;
use App\Repository\DrawnAreaRepository;
use App\Repository\DzkAdminOblRepository;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Exception\ConnectionException;
use Doctrine\DBAL\Exception\RetryableException;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\Finder\Exception\AccessDeniedException;

use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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
    public function drawMap(Request $request, SessionInterface  $seesion): Response
    {
        $flashBag = $seesion->getBag('flashes');
       // $form = $this->createForm(DrawnAreaType::class, new DrawnArea(), ['action' => $this->generateUrl('drawen.draw_add')]);
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
        if (!$flashBag->has('instruct_message')) {
            $flashBag->add('instruct_message', 'true');
            $instruct = 1;
        }
        return $this->render('statement/draw_map.html.twig', [
            //'form' => $form->createView(),
            'cc' => $cc, 'z' => $z, 'instruct' => $instruct]);
    }

    /**
     * @Route("/dr_profile", name="drawen.draw_profile")
     */
    public function profile(Request $request, EntityManagerInterface $em, TokenStorageInterface $tokenStorage): Response
    {
        {
            // $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

            try {
                $user = $tokenStorage->getToken()->getUser();
                $cond = (array)$user->getProfile();
                $profile = $cond ? $user->getProfile() : new Profile();
                //  $form = $this->createForm(ProfileTypeOLD::class, $profile);
                $form = $this->createFormBuilder($profile)
                    ->add('url', null, ['label' => 'Посилання на сайт'])
                    ->getForm();
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    $user->setProfile($profile);
                    //  $profile->setUsers($user);
                    $em->persist($profile);
                    $em->flush();
                    return new JsonResponse(['success' => true]);
                }
                $fullArea = $em->getRepository(DrawnArea::class)->countAreaByUser($user);
                $obl = "";
                if ($profile->getOblast()) {
                    $obl = $em->getRepository(DzkAdminObl::class)->getNameRgn($profile->getOblast()->getId());
                    $obl = $obl->getNameRgn();
                }
                return new JsonResponse(['content' => $this->render('statement/modals/swal_person.html.twig',
                    [
                        'profile' => $profile,
                        'fullArea' => round(((($fullArea['fullArea'] / 10000) * 100) / 100), 4),
                        'nameRgn' => $obl,
                        'areacount' => $user->getDrawnAreas()->count(),
                        'profileForm' => $form->createView()])->getContent(),
                ]);
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
                    'appl' => '<div>' . $drawnArea->getNumberSolution() . '</div><div>' . $drawnArea->getSolutedAt()->format('d-m-Y') . '</div>',
                    'area' => $drawnArea->getArea(),
                    'published' => $drawnArea->getPublishedAt()
                ]);
            }
            $profile = $this->getUser()->getProfile();
            if ($profile) {
                $form->get('address')->setData($profile->getAddress());
                $form->get('link')->setData($profile->getUrl());
            }
            $obl = "";
            if ($profile->getOblast()) {
                $obl = $em->getRepository(DzkAdminObl::class)->getNameRgn($profile->getOblast()->getId());
                $obl = $obl->getNameRgn();
            }
            $content = $this->renderView(
                'statement/modals/swal_area.html.twig',
                array('form' => $form->createView(), 'drawnArea' => $drawnArea, 'obl' => $obl)
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
     * @throws DBALException
     */
    public function upd($id, Request $request, EntityManagerInterface $em, DrawnAreaRepository $drawnAreaRepository, DzkAdminOblRepository $dzkAdminOblRepository): Response
    {
        try {
            $drawnArea = $drawnAreaRepository->getPartialObj($id);
            //dump($drawnArea);
            //$drawnArea->setGeom(null);
            if ($drawnArea->getAuthor() !== $this->getUser()) {
                throw new AccessDeniedException('Немає доступу до об\'єкту', Response::HTTP_NOT_ACCEPTABLE);
            }
            if ($drawnArea->getStatus() !== 'created') {
                $content = $this->renderView(
                    'statement/modals/swal_area_info.html.twig',
                    [
                        'drawnArea' => $drawnArea,
                        'obl' => $dzkAdminOblRepository->getNameRgn($drawnArea->getAuthor()->getProfile()->getOblast()->getId())->getNameRgn()
                    ]
                );
                $buttons = $this->renderView(
                    'statement/modals/swal_area_buttons.html.twig', ['drawnArea' => $drawnArea]);
                return new JsonResponse(['content' => $content, 'buttons' => $buttons]);
            }
            // перечитываем partial
            $em->refresh($drawnArea);
            $form = $this->createForm(DrawnAreaType::class, $drawnArea, [
                'entity_manager' => $em,
                'action' => $this->generateUrl('drawen.draw_upd', ['id' => $id]),
            ]);
            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                if (!$form->isValid()) {
                    throw  new Exception($this->getErrorsFromForm($form)[0], 412);
                    // throw new HttpException(412, $this->getErrorsFromForm($form)[0]);
                }
                $em->persist($drawnArea);
                $em->flush();
                $this->addFlash(
                    'success',
                    ['Виправлену інформацію внесено', date("d-m-Y H:i:s")]
                );
                return new JsonResponse(['success' => true, 'area'=>$drawnArea->getArea()], 200);
            }
            $content = $this->renderView(
                'statement/modals/swal_area.html.twig',
                array('form' => $form->createView(), 'drawnArea' => $drawnArea,
                    'obl' => $dzkAdminOblRepository->getNameRgn($drawnArea->getAuthor()->getProfile()->getOblast()->getId())->getNameRgn()
                )
            );
            $buttons = $this->renderView(
                'statement/modals/swal_area_buttons.html.twig', ['drawnArea' => $drawnArea]);
            return new JsonResponse(['content' => $content, 'buttons' => $buttons]);
        }
//        catch (DBALException $e) {
//            $message = sprintf('DBALException [%i]: %s', $e->getCode(), $e->getMessage());
//        } catch (PDOException $e) {
//            $message = sprintf('PDOException [%i]: %s', $e->getCode(), $e->getMessage());
//        }
//        catch (RetryableException $e) {
//            $e = FlattenException::create($e);
//            return $this->json(['error' => $e->getMessage()]
//                , $e->getStatusCode()
//            );
//        }

        catch (\Throwable $exception) {
            $exception = FlattenException::create($exception);
            return $this->json(['error' => $exception->getStatusCode() == 404 ? 'Method not found' : 'Unknown error occurred'], $exception->getStatusCode());
            //return Response::create($handler->getHtml($exception), $exception->getStatusCode(), $exception->getHeaders());
//                $this->json(['error' => $exception->getMessage()]
//                , $exception->getStatusCode()
//            );
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
            $exception = FlattenException::create($exception);
            return $this->json(['error' => $exception->getMessage()], $exception->getStatusCode());
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
            $exception = FlattenException::create($exception);
            return $this->json(['error' => $exception->getMessage()], $exception->getStatusCode());
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
            $exception = FlattenException::create($exception);
            return $this->json(['error' => $exception->getMessage()], $exception->getStatusCode());
        }
    }

    /**
     * @Route("/drawen_geoms", name="drawen.all_geoms", methods={"GET"}, condition="request.isXmlHttpRequest()")
     */
    public
    function allGeoms(DrawnAreaRepository $repository, SerializerInterface $serializer, Request $request): Response
    {
        try {
            $geoms = $serializer->serialize($repository->findBy(['author' => $this->getUser()]), 'json', ['groups' => 'geoms']);
            return JsonResponse::fromJsonString($geoms, Response::HTTP_OK, ['Access-Control-Allow-Origin' => 'same-origin']);
        } catch (Exception $exception) {
            $exception = FlattenException::create($exception);
            return $this->json(['error' => $exception->getMessage()], $exception->getStatusCode());
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
