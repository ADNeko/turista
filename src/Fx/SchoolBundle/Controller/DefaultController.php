<?php

namespace Fx\SchoolBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Default controller.
 *
 * @Security("has_role('ROLE_USER')")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="fx_school.default.index")
     */
    public function indexAction()
    {
        return $this->render('FxSchoolBundle:Default:index.html.twig');
    }


    /**
     * @Route("/coming_soon", name="fx_school.default.coming_soon")
     */
    public function comingSoonAction()
    {
        return $this->render('FxSchoolBundle:Default:coming_soon.html.twig');
    }
}
