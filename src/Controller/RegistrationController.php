<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setRoles(['ROLE_USER']);
            $user->setIsDisabled(true);
            $entityManager->persist($user);
            $entityManager->flush();

            $flashBag = $request->getSession()->getFlashBag();
            $flashBag->get('user-register-notice'); // gets message and clears type
            $flashBag->set('user-register-notice', 'Дякуємо за реєстрацію! Повідомлення про активацію облікового запису буде доставлено на Вашу пошту');
            $email = (new TemplatedEmail())
                ->from(new Address('sokolskiy@dzk.gov.ua', '"Drawer mail bot"'))
                ->to('sokolskiy@dzk.gov.ua')
                ->subject('Нова реєстрація '. $user->getEmail())
                ->htmlTemplate('email/registrationToAccount.html.twig');
            $context['user'] = $user;
            $email->context($context);
            $mailer->send($email);

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

//    /**
//     * @Route("/verify/email", name="app_verify_email")
//     */
//    public function verifyUserEmail(Request $request): Response
//    {
//    //    $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
//
//        // validate email confirmation link, sets User::isVerified=true and persists
////        try {
////            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
////        } catch (VerifyEmailExceptionInterface $exception) {
////            $this->addFlash('verify_email_error', $exception->getReason());
////
////            return $this->redirectToRoute('app_register');
////        }
//
//        // @TODO Change the redirect on success and handle or remove the flash message in your templates
//        $this->addFlash('success', 'Your email address has been verified.');
//
//        return $this->redirectToRoute('homepage');
//    }
}
