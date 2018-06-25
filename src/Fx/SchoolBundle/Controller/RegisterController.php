<?php

namespace Fx\SchoolBundle\Controller;

use Fx\SchoolBundle\Form\ImageType;
use Fx\SchoolBundle\Form\UsuarioRegisterType;
use Fx\SchoolBundle\Form\UsuarioType;
use Fx\SchoolBundle\Manager\ImageManager;
use Fx\SchoolBundle\Manager\UsuarioManager;
use Fx\SchoolBundle\Manager\visioManager;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\Serializer\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class RegisterController extends Controller
{


    /**
     * @DI\Inject("fx_school.usuario_manager")
     * @var $usuarioManager UsuarioManager
     */
    private $usuarioManager;



    /**
     * @Route("/registrarse", name="fx_school.registrar")
     */
    public function IndexAction(Request $request)
    {

        $document = $this->usuarioManager->newUsuario();
        $form = $this->createForm(new UsuarioRegisterType(), $document, array(
            'action' => $this->generateUrl('fx_school.registrar'),
            'method' => 'POST',
        ));
        try{
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->usuarioManager->addUsuario($document);
                $this->addFlash('success',"USUARIO REGISTRADO.");
                return $this->redirectToRoute('fx_school.default.index');
            }

        }
       catch (\Exception $exception){
           $this->addFlash('danger', $exception->getMessage());
       }
        return $this->render('FxSchoolBundle:Api:Registrarse.html.twig',array(
            'form' => $form->createView(),

        ));
    }
}
