<?php

namespace Fx\SchoolBundle\Manager;

use Doctrine\ORM\EntityManager;

use Fx\SchoolBundle\Entity\Image;;

use Fx\SchoolBundle\Exception\SchoolException;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Inject;
/**
 * @Service(id="fx_school.foto_manager")
 */
class ImageManager
{
    private $em;


    /**
     * @InjectParams({
     *     "em"     = @Inject("doctrine.orm.entity_manager"),
     * })
     */
    public function __construct(EntityManager $em, Logger $logger)
    {
        $this->em = $em;
        $this->logger = $logger;
    }

    public function newImage(){
       return new Image();
   }
   public function addFoto(Image $image){
        if(is_null($image->getFile())){
            throw new  SchoolException("NO PODEMOS CARGAR TU IMAGEN");
        }
       $this->em->persist($image);
       $this->em->flush();
    }

}
