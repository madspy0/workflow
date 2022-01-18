<?php

namespace App\Controller;

use App\Entity\DzkAdminObl;
use App\Entity\User;
use App\Repository\DevelopmentApplicationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Handler\DownloadHandler;

class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="account_index")
     * @param UserRepository $repository
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function index(UserRepository $repository, Request $request, EntityManagerInterface $em): Response
    {
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $repository->getPaginator($offset);
        return $this->render('account/index.html.twig', [
            'users' => $paginator,
            'repo' => $em->getRepository('Gedmo\Loggable\Entity\LogEntry'),
            'obls' => $em->getRepository(DzkAdminObl::class),
            'previous' => $offset - UserRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + UserRepository::PAGINATOR_PER_PAGE),
        ]);
    }

    /**
     * @Route("/account/enable/{user}", name="account_enable")
     * @param User $user
     * @param EntityManagerInterface $em
     * @param MailerInterface $mailer
     * @return JsonResponse
     */
    public function enable(User $user, EntityManagerInterface $em, MailerInterface $mailer): JsonResponse
    {
        try {
            if ($user->IsDisabled()) {
                $user->setIsDisabled(false);
                $status = 'enabled';
                $email = (new TemplatedEmail())
                    ->from(new Address('sokolskiy@dzk.gov.ua', '"Drawer mail bot"'))
                    ->to($user->getEmail())
                    ->subject('Підтвердження реєстрації')
                    ->htmlTemplate('email/enableAccount.html.twig');
                $context['user'] = $user;
                $email->context($context);
                $mailer->send($email);
            } else {
                $user->setIsDisabled(true);
                $status = 'disabled';
            }
            $em->persist($user);
            $em->flush();
            return new JsonResponse(['status' => $status], 200);
        } catch (TransportExceptionInterface $exception) {
            return $this->json(['error' => $exception->getMessage()], $exception->getCode());
        }
    }

    /**
     * @Route("/account/file/{user}", name="account_show_file")
     */
    public function showFile(User $user, DownloadHandler $downloadHandler)
    {
        return $downloadHandler->downloadObject($user->getProfile(), $fileField = 'ecpFile');
    }

    /**
     * @Route("/account/authorize/{user}", name="account_authorize")
     *
     * @param User $user
     * @param EntityManagerInterface $em
     * @param MailerInterface $mailer
     * @return JsonResponse
     * @throws TransportExceptionInterface
     */
    public function authorize(User $user, EntityManagerInterface $em, MailerInterface $mailer): JsonResponse
    {
        try {
            $user->setRoles(['ROLE_EDITOR']);
            $email = (new TemplatedEmail())
                ->from(new Address('sokolskiy@dzk.gov.ua', 'Drawer mail bot'))
                ->to($user->getEmail())
                ->subject('Підтвердження реєстрації')
                ->htmlTemplate('email/enableAccount.html.twig');
            $context['user'] = $user;
            $email->context($context);
            $mailer->send($email);
            $em->persist($user);
            $em->flush();
            return new JsonResponse(['status' => 'ok'], 200);
        } catch (Exception $exception) {
            return $this->json(['error' => $exception->getMessage()], $exception->getCode());
        }
    }

    /**
     * @Route("/account/deny/{user}", name="account_deny")
     *
     * @param User $user
     * @param EntityManagerInterface $em
     * @param MailerInterface $mailer
     * @return JsonResponse
     * @throws TransportExceptionInterface
     */
    public function deny(User $user, EntityManagerInterface $em, MailerInterface $mailer): JsonResponse
    {
        try {
            $email = (new TemplatedEmail())
                ->from(new Address('sokolskiy@dzk.gov.ua', 'Drawer mail bot'))
                ->to($user->getEmail())
                ->subject('Підтвердження реєстрації')
                ->htmlTemplate('email/enableAccount.html.twig');
            $context['user'] = $user;
            $email->context($context);
            $mailer->send($email);
            $em->remove($user);
            $em->flush();
            return new JsonResponse(['status' => 'ok'], 200);
        } catch (Exception $exception) {
            return $this->json(['error' => $exception->getMessage()], $exception->getCode());
        }
    }
}
