<?php

namespace Fx\SchoolBundle\Controller;

use FOS\UserBundle\Model\UserInterface;
use Fx\SchoolBundle\ControllerHelper\MyProfileControllerHelper;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * @Route("/my_profile")
 * @Security("has_role('ROLE_USER')")
 */
class MyProfileController extends Controller
{
    /**
     * @DI\Inject("fx_school.my_profile_controller_helper")
     * @var $helper MyProfileControllerHelper
     */
    private $helper;


    /**
     * @Route("/", name="fx_school.my_profile.index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $changePasswordForm = $this->helper->createChangePasswordForm($this->getUser());

        return $this->render('FxSchoolBundle:MyProfile:index.html.twig', array(
            'change_password_form' => $changePasswordForm->createView()
        ));
    }


    /**
     * @Route("/change_password", name="fx_school.my_profile.change_password")
     * @Method("POST")
     */
    public function changePasswordAction(Request $request)
    {
        $usuario = $this->getUser();
        if (!is_object($usuario) || !$usuario instanceof UserInterface) {
            throw new AccessDeniedHttpException('This user does not have access to this section.');
        }

        $changePasswordForm = $this->helper->createChangePasswordForm($usuario);

        $changePasswordForm->handleRequest($request);

        if ($changePasswordForm->isValid()) {

            $userManager = $this->get('fos_user.user_manager');

            $userManager->updateUser($usuario);

            $this->addFlash('success', "Contraseña cambiada con éxito.");

            return $this->redirectToRoute('fx_school.my_profile.index');
        }

        return $this->render('FxSchoolBundle:MyProfile:index.html.twig', array(
            'change_password_form' => $changePasswordForm->createView()
        ));
    }
}
