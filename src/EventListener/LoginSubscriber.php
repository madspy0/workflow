<?php

namespace App\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use App\Entity\User;
use Symfony\Component\Security\Http\SecurityEvents;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class LoginSubscriber implements EventSubscriberInterface
{
    private $em;
    private $flash;

    public function __construct(EntityManagerInterface $em, FlashBagInterface $flash)
    {
        $this->em = $em;
        $this->flash=$flash;
    }

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {

        $this->flash->add('successlogged_info', 'logged');
        // Get the User entity.
        $user = $event->getAuthenticationToken()->getUser();


        // Update your field here.
        $user->setCurrentAt(new \DateTimeImmutable());

        // Persist the data to database.
        $this->em->persist($user);
        $this->em->flush();
    }

    public static function getSubscribedEvents()
    {
        return [
            SecurityEvents::INTERACTIVE_LOGIN => 'onSecurityInteractiveLogin',
        ];
    }
}
