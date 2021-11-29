<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class AuthenticationEntryPoint implements AuthenticationEntryPointInterface
{
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        // add a custom flash message and redirect to the login page
        $h = $request->headers->all();
   //     dump($h);
  //      $request->getSession()->getFlashBag()->add('note', 'Ви повинні увійти, щоб отримати доступ.');
        if (("application/json" === $h['content-type'][0])||(strpos($h['content-type'][0],"multipart/form-data;")!==false)) {

            return new JsonResponse(['error' => "Ви повинні увійти, щоб отримати доступ"], Response::HTTP_NOT_ACCEPTABLE);
       }
        else {
            return new RedirectResponse($this->urlGenerator->generate('app_login'));
        }
    }
}