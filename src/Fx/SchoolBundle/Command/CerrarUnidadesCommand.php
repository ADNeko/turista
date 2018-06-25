<?php

namespace Fx\SchoolBundle\Command;

use Doctrine\ORM\EntityManager;
use Fx\SchoolBundle\Entity\Unidad;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Fx\SchoolBundle\Entity\Clase;

class CerrarUnidadesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('fx:cerrar-unidades')
            ->setDescription('Cerrar unidades ya vencidas');
    }

    public function CerrarVencido(Unidad $unidad){
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        if(!$unidad->getFinal()){
            $unidad->setEstado(Unidad::ESTADO_CERRADO);
            $em->persist($unidad);
            $em->flush();
            return true;
        }
        return false;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $contador=0;
        /* @var $em EntityManager */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $logger=$this->getContainer()->get('monolog.logger.school');
        $unidades = $em->getRepository('FxSchoolBundle:Unidad')->findBy(array(
           'estado' => Unidad::ESTADO_ACTIVO,
        ));


        /* @var $unidad Unidad */
        foreach ($unidades as $unidad) {
            $ok=$this->CerrarVencido($unidad);
            if($ok==true){
                $output->writeln($unidad->getId().'-'.date_format($unidad->getFechaFin(),'Y-m-d').'-'.'unidad cerrada');
                $contador=$contador+1;
            }
            else{
                $output->writeln($unidad->getId().'-'.date_format($unidad->getFechaFin(),'Y-m-d').'-'.'unidad no cerrada');
            }
        }
        $logger->info(sprintf("Se han cerrado %d unidades el dia  %s.",
            $contador,
            date('d-m-Y')
        ));
    }
}
