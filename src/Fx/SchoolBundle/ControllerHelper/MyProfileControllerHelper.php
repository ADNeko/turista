<?php

namespace Fx\SchoolBundle\ControllerHelper;

use FOS\UserBundle\Form\Type\ChangePasswordFormType;
use Fx\SchoolBundle\Entity\Usuario;
use Fx\SchoolBundle\Form\MiEscomapeType;
use Fx\SchoolBundle\Form\Model\MiEscomape;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Inject;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Routing\Router;

/**
 * @Service(id="fx_school.my_profile_controller_helper")
 */
class MyProfileControllerHelper
{
    private $formFactory;
    private $router;


    /**
     * @InjectParams({
     *     "formFactory"        = @Inject("form.factory"),
     *     "router"             = @Inject("router")
     * })
     */
    public function __construct(FormFactory $formFactory, Router $router)
    {
        $this->formFactory = $formFactory;
        $this->router      = $router;
    }


    public function createChangePasswordForm(Usuario $usuario)
    {
        return $this->formFactory->create(new ChangePasswordFormType('Fx\\SchoolBundle\\Entity\\Usuario'), $usuario, array(
            'action' => $this->router->generate('fx_school.my_profile.change_password'),
            'method' => 'POST',
        ));
    }
}
