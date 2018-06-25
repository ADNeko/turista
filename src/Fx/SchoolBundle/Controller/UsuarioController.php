<?php

namespace Fx\SchoolBundle\Controller;

use Fx\SchoolBundle\ControllerHelper\UsuarioControllerHelper;
use Fx\SchoolBundle\Entity\Usuario;
use Fx\SchoolBundle\Exception\SchoolException;
use Fx\SchoolBundle\Form\FxButtonType;
use Fx\SchoolBundle\Form\BuscarUsuarioType;
use Fx\SchoolBundle\Form\Model\BuscarUsuario;
use Fx\SchoolBundle\Form\Model\ConsultarDocumento;
use Fx\SchoolBundle\Form\UsuarioType;
use Fx\SchoolBundle\Manager\ConsultaManager;
use Fx\SchoolBundle\Manager\UsuarioManager;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/usuarios")
 * @Security("has_role('ROLE_ADMIN')")
 */
class UsuarioController extends Controller
{


    /**
     * @DI\Inject("fx_school.usuario_manager")
     * @var $usuarioManager UsuarioManager
     */
    private $usuarioManager;


    /**
     * @DI\Inject("fx_school.usuario_controller_helper")
     * @var $helper UsuarioControllerHelper
     */
    private $helper;


    /**
     * @Route("/", name="fx_school.usuario.index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $buscarUsuario = new BuscarUsuario();

        $form = $this->createForm(new BuscarUsuarioType(), $buscarUsuario, array(
            'action' => $this->generateUrl('fx_school.usuario.search'),
            'method' => 'GET',
        ));

        return $this->render('FxSchoolBundle:Usuario:index.html.twig', array(
            'search_form' => $form->createView(),
        ));
    }


    /**
     * @Route("/registrar", name="fx_school.usuario.new")
     * @Security("has_role('ROLE_ADMIN')")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $entity = $this->usuarioManager->newUsuario();



        $form = $this->createForm(new UsuarioType(), $entity, array(
            'action' => $this->generateUrl('fx_school.usuario.new'),
            'method' => 'POST',
            'validation_groups' => array('registration', 'Default'),
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->usuarioManager->addUsuario($entity);

            $request->getSession()->getFlashBag()->add(
                'success',
                '¡Usuario registrado!'
            );

            return $this->redirect($this->generateUrl('fx_school.usuario.show', array('id' => $entity->getId())));
        }

        return $this->render('FxSchoolBundle:Usuario:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }


    /**
     * @Route("/{id}", name="fx_school.usuario.show", requirements={"id": "\d+"})
     * @ParamConverter("entity", class="FxSchoolBundle:Usuario")
     * @Security("has_role('ROLE_ADMIN')")
     * @Method("GET")
     */
    public function showAction(Request $request, Usuario $entity)
    {
        $deleteForm = $this->createForm(new FxButtonType(), null, array(
            'action' => $this->generateUrl('fx_school.usuario.delete', array('id' => $entity->getId())),
            'method' => 'DELETE',
        ));

        return $this->render('FxSchoolBundle:Usuario:show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * @Route("/busqueda", name="fx_school.usuario.search")
     * @Security("has_role('ROLE_ADMIN')")
     * @Method("GET")
     */
    public function searchAction(Request $request)
    {
        $buscarUsuario = new BuscarUsuario();

        $form = $this->createForm(new BuscarUsuarioType(), $buscarUsuario, array(
            'action' => $this->generateUrl('fx_school.usuario.search'),
            'method' => 'GET'
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entities = $this->usuarioManager->buscarUsuarios($buscarUsuario);

            if (count($entities) === 1)
                return $this->redirect($this->generateUrl('fx_school.usuario.show', array('id' => $entities[0]->getId())));

            return $this->render('FxSchoolBundle:Usuario:search.html.twig', array(
                'entities'    => $entities,
                'search_form' => $form->createView(),
            ));
        }

        return $this->render('FxSchoolBundle:Usuario:index.html.twig', array(
            'search_form' => $form->createView(),
        ));
    }
    /**
     * @Route("/{id}", name="fx_school.usuario.delete", requirements={"id": "\d+"})
     * @ParamConverter("entity", class="FxSchoolBundle:Usuario")
     * @Method("DELETE")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request, Usuario $entity)
    {
        $deleteForm = $this->createForm(new FxButtonType(), null, array(
            'action' => $this->generateUrl('fx_school.usuario.delete', array('id' => $entity->getId())),
            'method' => 'DELETE',
        ));

        $deleteForm->handleRequest($request);

        if ($deleteForm->isSubmitted() && $deleteForm->isValid()) {
            $this->usuarioManager->removeUsuario($entity);

            $request->getSession()->getFlashBag()->add(
                'success',
                '¡Usuario eliminado!'
            );

            return $this->redirect($this->generateUrl('fx_school.usuario.index'));
        }

        return $this->render('FxSchoolBundle:Usuario:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @Route("/{id}/edit", name="fx_school.usuario.edit", requirements={"id": "\d+"})
     * @ParamConverter("entity", class="FxSchoolBundle:Usuario")
     * @Security("has_role('ROLE_ADMIN')")
     * @Method({"GET", "PUT"})
     */
    public function editAction(Request $request, Usuario $entity)
    {
        $form = $this->createForm(new UsuarioType(), $entity, array(
            'action'            => $this->generateUrl('fx_school.usuario.edit', array('id' => $entity->getId())),
            'method'            => 'PUT',
            'validation_groups' => array('edit', 'Default'),
        ));

        $rol = $entity->getRol();

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $this->usuarioManager->updateUsuario($entity);

            $request->getSession()->getFlashBag()->add(
                'success',
                '¡Usuario actualizado!'
            );

            return $this->redirect($this->generateUrl('fx_school.usuario.show', array('id' => $entity->getId())));
        }

        return $this->render('FxSchoolBundle:Usuario:edit.html.twig', array(
            'entity'    => $entity,
            'edit_form' => $form->createView(),
        ));
    }

}

