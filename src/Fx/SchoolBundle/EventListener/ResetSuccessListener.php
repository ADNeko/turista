<?php

namespace Fx\SchoolBundle\EventListener;

use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\UserEvent;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\Tag;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @Service
 * @Tag("kernel.event_subscriber")
 */
class ResetSuccessListener implements EventSubscriberInterface
{
    private $router;


    /**
     * @InjectParams({
     *     "router"          = @Inject("router")
     * })
     */
    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }


    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::RESETTING_RESET_SUCCESS => 'onResetSuccess'
        );
    }


    public function onResetSuccess(FormEvent $event)
    {
        $url = $this->router->generate('fx_school.default.index');

        $event->setResponse(new RedirectResponse($url));
    }
}
