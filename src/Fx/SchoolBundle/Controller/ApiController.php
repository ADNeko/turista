<?php

namespace Fx\SchoolBundle\Controller;

use Fx\SchoolBundle\ControllerHelper\UsuarioControllerHelper;
use Fx\SchoolBundle\Entity\Image;
use Fx\SchoolBundle\Entity\Usuario;
use Fx\SchoolBundle\Exception\SchoolException;
use Fx\SchoolBundle\Form\FxButtonType;
use Fx\SchoolBundle\Form\BuscarUsuarioType;
use Fx\SchoolBundle\Form\ImageType;
use Fx\SchoolBundle\Form\Model\BuscarUsuario;
use Fx\SchoolBundle\Form\UsuarioType;
use Fx\SchoolBundle\Manager\UsuarioManager;
use Fx\SchoolBundle\Manager\visioManager;
use Fx\SchoolBundle\Manager\ImageManager;
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
 * @Route("/api")
 */
class ApiController extends Controller
{

    /**
     * @DI\Inject("fx_school.visio_manager")
     * @var $visioManager visioManager
     */
    private $visioManager;

    /**
     * @DI\Inject("fx_school.foto_manager")
     * @var $imageManager ImageManager
     */
    private $imageManager;



    /**
     * @Route("/", name="fx_tourist.foto.subir2")
     */
    public function IndexAction(Request $request)
    {

        $document = $this->imageManager->newImage();
        $form = $this->createForm(new ImageType(), $document, array(
            'action' => $this->generateUrl('fx_tourist.foto.subir2'),
            'method' => 'POST',
        ));
        try{
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->imageManager->addFoto($document);
                $this->addFlash('success', "Foto Analizada.");

                return $this->render('FxSchoolBundle:Api:subir.html.twig',array(
                    'form' => $form->createView(),
                    'image' => $document,
                    'resultados' => $this->visioManager->getDatos($document)
                ));
            }

        }
        catch (\Exception $exception){
            $this->addFlash('danger', $exception->getMessage());
        }
        return $this->render('FxSchoolBundle:Api:subir.html.twig',array(
            'form' => $form->createView(),
            'resultados' => null,
            'image'     => null
        ));
    }

}

