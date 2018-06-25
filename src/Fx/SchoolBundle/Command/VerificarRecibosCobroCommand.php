<?php

namespace Fx\SchoolBundle\Command;

use Doctrine\ORM\EntityManager;
use Fx\SchoolBundle\Entity\Horario;
use Fx\SchoolBundle\Entity\ReciboCobro;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Fx\SchoolBundle\Entity\Clase;

class VerificarRecibosCobroCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('fx:verificar-recibo-cobro')
            ->setDescription('Verificar Recibos de Cobro');
    }

    public function CerrarVencido(ReciboCobro $cobro){
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        if(!$cobro->getFinalCobro()){
            $cobro->setEstado(ReciboCobro::ESTADO_VENCIDO);
            $em->persist($cobro);
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
            ->select('rc')
            ->from('FxSchoolBundle:ReciboCobro', 'rc')
            ->where('rc.estado = :estado')
            ->setParameter('estado', ReciboCobro::ESTADO_PENDIENTE)
            ->getQuery();
        $recibos = $query->getResult();
        /** @var ReciboCobro $recibo */
        foreach ($recibos as $recibo) {

            $ok=$this->CerrarVencido($recibo);

                if($ok==true){
                    $output->writeln($recibo->getId().'-'.date_format($recibo->getFechaFin(),'Y-m-d').'-'.'recibo Vencido');
                    $contador=$contador+1;
                }

            }

        $output->writeln(sprintf("Se han vencido %d pagos el dia  %s.",
            $contador,
            date('d-m-Y')
        ));

        $logger->info(sprintf("Se han vencido %d pagos el dia  %s.",
            $contador,
            date('d-m-Y')
        ));
    }
}
