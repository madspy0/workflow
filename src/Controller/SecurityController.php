<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, FlashBagInterface $flashBag): Response
    {
        if ($this->getUser() && in_array('ROLE_ACCOUNT', $this->getUser()->getRoles(), true)) {
             return $this->redirectToRoute('account_index');
         }
//        if ($this->getUser() && in_array('ROLE_WAIT', $this->getUser()->getRoles(), true)) {
//            return $this->redirectToRoute('app_register_access_file');
//        }
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error,
            'noticies' => $flashBag->get('user-register-notice'), 'recaptchaSite' => $this->getParameter('recaptcha.site') ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
