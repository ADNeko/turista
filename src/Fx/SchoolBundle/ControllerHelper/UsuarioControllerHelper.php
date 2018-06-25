<?php

namespace Fx\SchoolBundle\ControllerHelper;

use Fx\SchoolBundle\Entity\Usuario;
use Fx\SchoolBundle\Form\FxButtonType;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Inject;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Service(id="fx_school.usuario_controller_helper")
 */
class UsuarioControllerHelper
{
    private $formFactory;
    private $router;


    /**
     * @InjectParams({
     *     "formFactory"     = @Inject("form.factory"),
     *     "router"          = @Inject("router")
     * })
     */
    public function __construct(FormFactory $formFactory, Router $router)
    {
        $this->formFactory = $formFactory;
        $this->router = $router;
    }


    //region Create forms
    public function createDeshabilitarForm(Usuario $usuario)
    {
        return $this->formFactory->create(new FxButtonType(), null, array(
            'action' => $this->router->generate('fx_school.usuario.disable', array(
                'usuario_id' => $usuario->getId(),
            )),
            'method' => 'POST',
        ));
    }


    public function createHabilitarForm(Usuario $usuario)
    {
        return $this->formFactory->create(new FxButtonType(), null, array(
            'action' => $this->router->generate('fx_school.usuario.enable', array(
                'usuario_id' => $usuario->getId(),
            )),
            'method' => 'POST',
        ));
    }
    //endregion
}
