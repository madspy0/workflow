<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Vich\UploaderBundle\Form\Type\VichFileType;

class RegistrationController extends AbstractController
{

    /**
     * @Route("/register", name="app_register")
     *
     */
    public function register(Request                     $request,
                             UserPasswordHasherInterface $userPasswordHasher,
                             EntityManagerInterface      $entityManager,
                             UserAuthenticatorInterface $authenticator,
                             LoginFormAuthenticator $formAuthenticator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user, ['entity_manager'=>$this->getDoctrine()->getManager()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setRoles(['ROLE_WAIT']);
            $entityManager->persist($user);
            $entityManager->flush();

   //        return $this->redirectToRoute('app_register_access_file');
            return $authenticator->authenticateUser(
                $user,
                $formAuthenticator,
                $request);
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/register/file", name="app_register_access_file")
     *
     * @throws TransportExceptionInterface
     */
    public function accessFile(Request $request, EntityManagerInterface $em, MailerInterface             $mailer): Response
    {
        $profile = $this->getUser()->getProfile();
        $form = $this->createFormBuilder($profile)
//            ->setMethod('POST')
            ->add('ecpFile', VichFileType::class, [
//                'required' => false,
                'allow_delete' => false,
//                'delete_label' => '...',
//                'download_uri' => '/register/file',
//                'download_label' => 'Завантажити',
                'asset_helper' => true,
                'label' => false
            ])
            ->add('download', SubmitType::class, ['label'=>'Завантажити'])
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
//            dump($profile);
            $em->persist($profile);
            $em->flush();
            $flashBag = $request->getSession()->getFlashBag();
            $flashBag->get('user-register-notice'); // gets message and clears type
            $flashBag->set('user-register-notice', 'Дякуємо за реєстрацію! Повідомлення про активацію облікового запису буде доставлено на Вашу пошту');

            $email = (new TemplatedEmail())
                ->from(new Address('sokolskiy@dzk.gov.ua', '"Drawer mail bot"'))
                ->to('sokolskiy@dzk.gov.ua')
                ->subject('Нова реєстрація ' . $this->getUser()->getEmail())
                ->htmlTemplate('email/registrationToAccount.html.twig');
            $context['user'] = $this->getUser();
            $email->context($context);
            $mailer->send($email);
            return $this->redirectToRoute('app_login');
        }
        return $this->render('security/access-file.html.twig',['form'=>$form->createView()]);
    }

    /**
     * @Route("/register/file/download", name="app_register_access_file_download")
     **/
    public function accessFileDownload(): BinaryFileResponse
    {
        return (new BinaryFileResponse($this->getParameter('kernel.project_dir') . '/assets/docs/granting_access.docx'));
    }
}
