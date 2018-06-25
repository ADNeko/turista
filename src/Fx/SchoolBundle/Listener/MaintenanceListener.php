<?php

namespace Fx\SchoolBundle\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MaintenanceListener
{
    private $container;


    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }


    public function onKernelRequest(GetResponseEvent $event)
    {
        $maintenanceUntil = $this->container->hasParameter('under_maintenance_until') ? $this->container->getParameter('under_maintenance_until') : false;
        $maintenance      = $this->container->hasParameter('maintenance') ? $this->container->getParameter('maintenance') : false;

        $debug = in_array($this->container->get('kernel')->getEnvironment(), array('test', 'dev'));

        if ($maintenance && !$debug) {
            $engine  = $this->container->get('templating');
            $content = $engine->render('::maintenance.html.twig', array('maintenance_until' => $maintenanceUntil));
            $event->setResponse(new Response($content, 503));
            $event->stopPropagation();
        }
    }
}
