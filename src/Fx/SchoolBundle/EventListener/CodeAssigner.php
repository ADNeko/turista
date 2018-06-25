<?php

namespace Fx\SchoolBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Fx\SchoolBundle\Entity\Estudiante;
use Fx\SchoolBundle\Entity\Inscripcion;
use Fx\SchoolBundle\Entity\LocalCarrera;
use Fx\SchoolBundle\Entity\LocalCarreraAnho;
use Fx\SchoolBundle\Entity\Prematricula;
use Fx\SchoolBundle\Entity\ReciboCobro;
use Fx\SchoolBundle\Entity\Salida;
use Fx\SchoolBundle\Utils\CuiGenerator;
use Fx\UgelBundle\Entity\FichaInscripcion;
use Fx\UgelBundle\Entity\NominaEstudiante;
use Fx\UgelBundle\Exception\UgelException;
use Hashids\Hashids;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;
use Monolog\Logger;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @Service("fx_school.code_assigner")
 * @Tag("doctrine.event_listener", attributes = {"event" = "postPersist"})
 */
class CodeAssigner
{
    private $logger;
    private $cuiGenerator;
    private $hashids;


    /**
     * @InjectParams({
     *     "logger"                 = @Inject("monolog.logger.school"),
     *     "cuiGenerator"           = @Inject("fx_school.cui_generator"),
     *     "hashids"                = @Inject("hashids")
     * })
     */
    public function __construct(Logger $logger, CuiGenerator $cuiGenerator, Hashids $hashids)
    {
        $this->logger       = $logger;
        $this->cuiGenerator = $cuiGenerator;
        $this->hashids      = $hashids;
    }


    public function postPersist(LifecycleEventArgs $args)
    {
        $entity        = $args->getEntity();
        $entityManager = $args->getEntityManager();

        if ($entity instanceof Estudiante) {
            $entity->setCodigo($this->cuiGenerator->getCui($entity->getId()));
            $entity->setEmail((string)$entity->getCodigo().$entity->getEmail());
            $entityManager->persist($entity);
            $entityManager->flush();
        } else if ($entity instanceof NominaEstudiante) {
            $inscripcion= $entity->getInscripcion();
            /** @var LocalCarrera $localCarrera */
            $localCarrera=$entityManager->getRepository('FxSchoolBundle:LocalCarrera')->findOneBy(array(
                'local' => $inscripcion->getLocal(),
                'carrera' => $inscripcion->getCarrera()
                ));
            if(is_null($localCarrera))throw new UgelException("CARRERA NO HABILITADA EN SISTEMA");

            if( is_null($inscripcion->getCodigoInscripcionUgel())) {
                if ($localCarrera->getCarrera()->getTipo()==LocalCarrera::CETPRO) {
                    $qb = $entityManager->createQueryBuilder('lca')
                        ->select('lca')
                        ->from('FxSchoolBundle:LocalCarreraAnho', 'lca')
                        ->leftJoin('lca.carreras', 'c')
                        ->where('lca.local = :local')
                        ->andwhere('c.id = :carrera')
                        ->andWhere('lca.anho = :anho')
                        ->setParameters(array(
                            'local'       => $inscripcion->getLocal(),
                            'carrera'     => $inscripcion->getCarrera(),
                            'anho'        => $entity->getNomina()->getAnho(),
                        ))
                        ->orderBy('lca.anho', 'ASC')
                        ->setMaxResults(1);
                    /** @var LocalCarreraAnho $lc */
                    $lc=$qb->getQuery()->getOneOrNullResult();

                    if(is_null($lc))throw new UgelException("SERIE DE CODIGOS NO CREADA.CONTACTE CON UN SUPERVISOR");
                    $codigoUgel = null;
                    $estado = false;
                    while (!$estado) {
                        $lc->setUltimo((int)$lc->getUltimo() + 1);
                        $codigoUgel = $lc->getResolucionCreacion() . $lc->getUltimoCodigoString() . substr($lc->getAnho(),-2);
                        $ins = $entityManager->getRepository('FxSchoolBundle:Inscripcion')->findOneBy(
                            array(
                                'codigoInscripcionUgel' => $codigoUgel
                            ));
                        if (is_null($ins)) {
                            $estado = true;
                        }
                    }
                    $entity->setCodigoInscripcion($codigoUgel);
                    $inscripcion->setCodigoInscripcionUgel($codigoUgel);
                    $entityManager->persist($inscripcion);
                    $entityManager->persist($entity);
                    $entityManager->persist($lc);
                    $entityManager->flush();
                }
                else{
                        $entity->setCodigoInscripcion($inscripcion->getEstudiante()->getDocumento());
                        $inscripcion->setCodigoInscripcionUgel($inscripcion->getEstudiante()->getDocumento());
                        $entityManager->persist($inscripcion);
                        $entityManager->persist($entity);
                        $entityManager->flush();
                    }
                }
                else{
                    $entity->setCodigoInscripcion($inscripcion->getCodigoInscripcionUgel());
                    $entityManager->persist($entity);
                    $entityManager->flush();
                }
                if($entity instanceof  FichaInscripcion){
                    $entity->setCodigoInscripcion($entity->getInscripcion()->getCodigoInscripcionUgel());
                    $entityManager->persist($entity);
                    $entityManager->flush();
                }
            }
        else if ($entity instanceof Prematricula) {
            $entity->setCodigo($this->hashids->encode($entity->getId()));
            $entityManager->persist($entity);
            $entityManager->flush();
        }
        else if ($entity instanceof ReciboCobro) {
            $entity->setNumeroRecibo(date('Y').$this->hashids->encode($entity->getId()));
            $entityManager->persist($entity);
            $entityManager->flush();
        }
        else if ($entity instanceof Salida){
            $entity->setCodigo($this->cuiGenerator->getCui($entity->getId()));
            $entityManager->persist($entity);
            $entityManager->flush();
        }

    }
}
