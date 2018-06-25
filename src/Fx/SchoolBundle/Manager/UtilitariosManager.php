<?php

namespace Fx\SchoolBundle\Manager;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Fx\SchoolBundle\Entity\Clase;
use Fx\SchoolBundle\Entity\Estudiante;
use Fx\SchoolBundle\Entity\Practica;
use Fx\SchoolBundle\Entity\Practicante;
use Fx\SchoolBundle\Entity\Reprogramacion;
use Fx\SchoolBundle\Entity\Usuario;
use Fx\SchoolBundle\Exception\SchoolException;
use Fx\SchoolBundle\Form\Model\BuscarClasesDocente;
use Fx\SchoolBundle\Repository\ClaseRepository;
use Fx\SchoolBundle\Utils\FxUtils;
use Liuggio\ExcelBundle\Factory;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Inject;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @Service(id="fx_school.utilitarios_manager")
 */
class UtilitariosManager
{
    private $em;
    private $tokenStorage;
    private $logger;
    private $fxUtils;
    private $phpexcel;
    private $kernel;
    private $templateDir;

    /**
     * @InjectParams({
     *     "em"             = @Inject("doctrine.orm.entity_manager"),
     *     "tokenStorage"      = @Inject("security.token_storage"),
     *     "logger"         = @Inject("monolog.logger.school"),
     *     "fxUtils"           = @Inject("fx_school.fx_utils"),
     *     "phpexcel"          = @Inject("phpexcel"),
     *     "kernel"            = @Inject("kernel")
     * })
     */
    public function __construct(EntityManager $em,TokenStorageInterface $tokenStorage, Logger $logger,FxUtils $fxUtils,Factory $phpexcel, $kernel)
    {
        $this->em     = $em;
        $this->tokenStorage = $tokenStorage;
        $this->logger = $logger;
        $this->fxUtils = $fxUtils;
        $this->phpexcel = $phpexcel;
        $this->kernel = $kernel;
        $this->templateDir = $this->kernel->getRootDir() . '/../src/Fx/UgelBundle/Resources/xlsx';
    }


    //    FUNCION PARA EXTRAER EL USUARIO LOGEADO
    public function getUserLogeado(){
        /** @var Usuario $usuario */
        $usuario=$this->tokenStorage->getToken()->getUser();

        return $usuario;
    }
}
