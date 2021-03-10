<?php

namespace App\EventSubscriber;

use App\Repository\UserRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Twig\Environment;
use Symfony\Component\Security\Core\Security;

class TwigEventSubscriber implements EventSubscriberInterface
{
    private $twig;  
    private $security;

    public function __construct(Environment $twig, Security $security )
    {
        $this->twig = $twig;
        $this->security = $security;
    }

    public function onControllerEvent(ControllerEvent $event)
    {
        $this->twig->addGlobal('user', $this->security->getUser());
    }

    public static function getSubscribedEvents()
    {
        return [
            ControllerEvent::class => 'onControllerEvent',
        ];
    }
}
