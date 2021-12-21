<?php

namespace App\Security;

use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    private $urlGenerator;
    private $params;

    public function __construct(UrlGeneratorInterface $urlGenerator, ContainerBagInterface $params)
    {
        $this->urlGenerator = $urlGenerator;
        $this->params=$params;
    }

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws Exception
     */
    public function authenticate(Request $request): PassportInterface
    {
        $recaptchaResponse = $request->get('g-recaptcha-response');
        if(!$recaptchaResponse) {
            throw new Exception('recaptcha_error');
        }
        $client = HttpClient::create();
        try {
            $response = $client->request('POST', 'https://www.google.com/recaptcha/api/siteverify?secret=' . $this->params->get('recaptcha.secret') . '&response=' . $recaptchaResponse);
        } catch (NotFoundExceptionInterface|ContainerExceptionInterface|TransportExceptionInterface $e) {
        }
        $jsonResponse = json_decode($response->getContent());
        if($jsonResponse->success !== true) {
            throw new Exception('recaptcha_error');
        }

        $email = $request->request->get('email', '');

        $request->getSession()->set(Security::LAST_USERNAME, $email);

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->get('_csrf_token')),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if(in_array('ROLE_WAIT', $token->getRoleNames()))
        {
            return new RedirectResponse('/register/file');
        }
        if(in_array('ROLE_ACCOUNT', $token->getRoleNames()))
        {
            return new RedirectResponse('/account/');
        }
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // For example:
        return new RedirectResponse($this->urlGenerator->generate('homepage'));
       // return null;
 //       throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
