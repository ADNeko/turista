<?php

namespace Fx\SchoolBundle\Command;

use Doctrine\ORM\EntityManager;
use Fx\SchoolBundle\Entity\Horario;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Fx\SchoolBundle\Entity\Clase;

class CerrarClasesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('fx:cerrar-clases')
            ->setDescription('Cerrar clases ya vencidas');
    }

    public function CerrarVencido(Clase $clase){
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        if(!$clase->getFinal()){
            $clase->setEstado(Clase::ESTADO_CERRADO);
            $em->persist($clase);
            $em->flush();
            return true;
        }
        return false;
    }
    public function CerrarVencidoRecuperacion(Clase $clase){
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        if(!$clase->getFinalRecuperaciones()){
            $clase->setEstado(Clase::ESTADO_CERRADO);
            $em->persist($clase);
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
        $query = $em->createQueryBuilder()
            ->select('c')
            ->from('FxSchoolBundle:Clase', 'c')
            ->where('c.estado = :estado')
            ->setParameter('estado', Clase::ESTADO_ACTIVO)
            ->getQuery();
        $clases = $query->getResult();
        /* @var $clase Clase */
        foreach ($clases as $clase) {
            if(!($clase->getCiclo()->getId()>245 && $clase->getCiclo()->getId()<248)){
                if($clase->getCiclo()->getHorario()->getTipo()==Horario::ESTADO_RECUPERACION){
                    $ok=$this->CerrarVencidoRecuperacion($clase);
                }
                else{
                    $ok=$this->CerrarVencido($clase);
                }
                if($ok==true){
                    $output->writeln($clase->getId().'-'.date_format($clase->getFechaFin(),'Y-m-d').'-'.'clase cerrada');
                    $contador=$contador+1;
                }
                else{
                    $output->writeln($clase->getId().'-'.date_format($clase->getFechaFin(),'Y-m-d').'-'.'clase no cerrada');
                }
            }

        }
        $logger->info(sprintf("Se han cerrado %d clases el dia  %s.",
            $contador,
            date('d-m-Y')
        ));
    }
}
