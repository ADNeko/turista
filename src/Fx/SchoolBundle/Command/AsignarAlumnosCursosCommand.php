<?php

namespace Fx\SchoolBundle\Command;

use Doctrine\ORM\EntityManager;
use Fx\AccountingBundle\Manager\CajaManager;
use Fx\SchoolBundle\Entity\BoletaNotas;
use Fx\SchoolBundle\Entity\Ciclo;
use Fx\SchoolBundle\Entity\Clase;
use Fx\SchoolBundle\Entity\Curso;
use Fx\SchoolBundle\Entity\Horario;
use Fx\SchoolBundle\Entity\Inscripcion;
use Fx\SchoolBundle\Entity\Matricula;
use Fx\SchoolBundle\Entity\Periodo;
use Fx\SchoolBundle\Manager\MatriculaManager;
use Fx\SchoolBundle\Manager\PensionManager;
use Fx\SchoolBundle\Repository\MatriculaRepository;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Fx\AccountingBundle\Entity\Caja;

class AsignarAlumnosCursosCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('fx:curso-alumnos')
            ->setDescription('Script para ingresar notas antiguas');
    }
    protected  function CrearDB(&$ciclo,&$db,&$cursos,$filename){
        $ignore=true;
        $datosCarrera=true;
        $fp = fopen($filename, "r");
        while(!feof($fp)) {
            $linea = fgets($fp);
            $datos=explode(",",$linea);
            if($datosCarrera){
                foreach ($datos as $lin){
                    if($lin){
                        $ciclo[]=$lin;
                    }
                }
                $datosCarrera=false;
            }
            else{
                if($ignore){
                    foreach ($datos as $lin){
                        if($lin){
                            $cursos[]=$lin;
                        }
                    }
                    $ignore=false;
                }
                else {
                    if (current($datos)) {
                        $alumno = current($datos);
                        next($datos);
                        foreach ($cursos as $curso) {
                            $db[] = array(
                                'alumno' => $alumno,
                                'curso' => $curso,
                                'nota' => current($datos)
                            );
                            next($datos);
                        }
                        reset($datos);
                    }
                }
            }

        }
        fclose($fp);

    }
    protected  function InsertarDB($archivo, OutputInterface $output){
        $ciclo=array();
        $cursos=array();
        $db=array();
        /* @var $em EntityManager */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        /** @var PensionManager $pensionManager */
        $pensionManager = $this->getContainer()->get('fx_school.pension_manager');

        $this->CrearDB($ciclo,$db,$cursos,$archivo);

        $anho=$ciclo[0];
        /** @var Carrera $carrera */
        $carrera=$em->getRepository('FxSchoolBundle:Carrera')->find($ciclo[1]);
        /** @var Horario $horario */
        $horario=$em->getRepository('FxSchoolBundle:Horario')->find($ciclo[3]);
        /** @var Periodo $periodo */
        $periodo=$em->getRepository('FxSchoolBundle:Periodo')->find($ciclo[2]);
        $aula=$ciclo[4];
        /** @var Ciclo $ciclo */
        $ciclo=$em->getRepository('FxSchoolBundle:Ciclo')->findOneBy(array(
            'anho'=>$anho,
            'periodo'=>$periodo,
            'carrera'=>$carrera,
            'local'  => $periodo->getLocal()
        ));
        if(is_null($ciclo)){
            $ciclo=new Ciclo();
            $ciclo->setPeriodo($periodo);
            $ciclo->setHorario($horario);
            $ciclo->setAnho($anho);
            $ciclo->setCarrera($carrera);
            $ciclo->setLocal($periodo->getLocal());
            $em->persist($ciclo);
        }
        /** @var MatriculaRepository $matriculaRepository */
        $matriculaRepository = $em->getRepository('FxSchoolBundle:Matricula');

        foreach ($cursos as $cur){
            $matriculados=array_filter($db, function($k) use ($cur){return $k['curso'] ==$cur ;});
            /** @var Curso $curso */
            $curso=$em->getRepository('FxSchoolBundle:Curso')->find($cur);

            if($curso){
                $clase=new Clase();
                $clase->setEstado(Clase::ESTADO_ACTIVO);
                $clase->setFechaInicio(new \DateTime());
                $clase->setFechaFin(new \DateTime());
                $clase->setAula($aula);
                $clase->setCurso($curso);
                $clase->setCiclo($ciclo);
                $ciclo->addClase($clase);
                $em->persist($clase);
                $em->persist($ciclo);
                $em->flush();
                $output->writeln($ciclo->getAnho()."-".$ciclo->getPeriodo()->getNombre()."-".$ciclo->getHorario()."-".$curso->getNombre().'-'.$clase->getAula());
                foreach ($matriculados as $alumno){
                    /** @var Inscripcion $inscripcion */
                    $inscripcion=$em->getRepository('FxSchoolBundle:Inscripcion')->find($alumno['alumno']);
                    $matriculas=$matriculaRepository->findByCursoAndInscripcionAndNoDeshabilitado($clase->getCurso(), $inscripcion);
                    if(count($matriculas)==0){
                        $pension=$pensionManager->getPensionModoCiclo($inscripcion,$anho,$periodo);
                        $matricula=new Matricula($clase,$inscripcion);
                        $matricula->setPension($pension);
                        $pension->addMatricula($matricula);
                        $matricula->setEstado(Matricula::ESTADO_HABILITADO);
                        $matricula->setFechaMatricula(new \DateTime());
                        $em->persist($matricula);
                        $em->persist($pension);
                        $boleta=new BoletaNotas();
                        $boleta->setPromedio($alumno['nota']);
                        $boleta->setFechaUltimaModificacion(new \DateTime());
                        $boleta->setTipo(BoletaNotas::INSTITUTO);
                        $matricula->setBoletaNotas($boleta);
                        $em->persist($boleta);
                        $em->flush();
                    }
                }
            }
            else{
                $output->writeln('curso que falla id '. $cur);
            }
        }
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $links=array();


        $links[]="http://intranet.flavisur.edu.pe/bundles/fxschool/images/notas/2017/I/555.csv";






        foreach ($links as $link){
            $this->InsertarDB($link,$output);
        }
    }
};


