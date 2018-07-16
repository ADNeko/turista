<?php

namespace Fx\SchoolBundle\Manager;

use Doctrine\ORM\EntityManager;

use Fx\SchoolBundle\Entity\Image;;

use Fx\SchoolBundle\Entity\Usuario;
use Fx\SchoolBundle\Exception\SchoolException;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Inject;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @Service(id="fx_school.foto_manager")
 */
class ImageManager
{
    private $em;
    private $tokenStorage;

    /**
     * @InjectParams({
     *     "em"     = @Inject("doctrine.orm.entity_manager"),
     *     "tokenStorage"    = @Inject("security.token_storage")
     * })
     */
    public function __construct(EntityManager $em,TokenStorageInterface $tokenStorage)
    {
        $this->em = $em;
        $this->tokenStorage=$tokenStorage;

    }

    public function newImage(){
       return new Image();
   }
    //    FUNCION PARA EXTRAER EL USUARIO LOGEADO
    public function getUserLogeado(){
        /** @var Usuario $usuario */
        $usuario=$this->tokenStorage->getToken()->getUser();

        return $usuario;
    }
   public function addFoto(Image $image){
        if(is_null($image->getFile())){
            throw new  SchoolException("NO PODEMOS CARGAR TU IMAGEN");
        }



       $this->em->persist($image);
       $this->em->flush();
    }
    public function addFotoUser(Image $image){
        if(is_null($image->getFile())){
            throw new  SchoolException("NO PODEMOS CARGAR TU IMAGEN");
        }

        $image->setUsuario($this->getUserLogeado());

        $this->em->persist($image);
        $this->em->flush();
    }
    public function getImageUser(){
        /** @var Usuario $usuario */
        $usuario=$this->tokenStorage->getToken()->getUser();

        $resultados=$this->em->getRepository('FxSchoolBundle:Image')->findBy(array(
           'usuario' => $usuario
        ));
        return $resultados;
    }
}
