<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Handler\DownloadHandler;

/**
 * @Route("/account", name="account")
 */
class AccountController extends AbstractController
{
    /**
     * @Route("/", name="account_index")
     */
    public function index(UserRepository $repository, EntityManagerInterface $em): Response
    {
        return $this->render('account/index.html.twig', [
            'users' => $repository->findAll(),
            'repo' => $em->getRepository('Gedmo\Loggable\Entity\LogEntry')
        ]);
    }

    /**
     * @Route("/enable/{user}", name="_enable")
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
        } catch (Exception $exception) {
            return $this->json(['error' => $exception->getMessage()], $exception->getCode());
        }
    }

    /**
     * @Route("/file/{user}", name="_show_file")
     */
    public function showFile(User $user, DownloadHandler $downloadHandler)
    {
        return $downloadHandler->downloadObject($user->getProfile(), $fileField = 'ecpFile');
    }

    /**
     * @Route("/authorize/{user}", name="_authorize")
     */
    public function authorize(User $user, EntityManagerInterface $em, MailerInterface $mailer): JsonResponse
    {
        try {
            $user->setRoles(['ROLE_EDITOR']);
            $em->persist($user);
            $em->flush();
            return new JsonResponse(['status' => 'ok'], 200);
        } catch (Exception $exception) {
            return $this->json(['error' => $exception->getMessage()], $exception->getCode());
        }
    }
}
