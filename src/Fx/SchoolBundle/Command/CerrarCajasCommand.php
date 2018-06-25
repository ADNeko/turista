<?php

namespace Fx\SchoolBundle\Command;

use Doctrine\ORM\EntityManager;
use Fx\AccountingBundle\Manager\CajaManager;
use Proxies\__CG__\Fx\SchoolBundle\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Fx\AccountingBundle\Entity\Caja;

class CerrarCajasCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('fx:cerrar-cajas')
            ->setDescription('Cerrar cajas');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $contador=0;
        /* @var $em EntityManager */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        /** @var CajaManager $manager */
        $manager = $em->getRepository('FxAccountingBundle:Caja');

        $logger=$this->getContainer()->get('monolog.logger.school');
        $query = $em->createQueryBuilder()
            ->select('c')
            ->from('FxAccountingBundle:Caja', 'c')
            ->where('c.estado = :estado')
            ->setParameter('estado',Caja::ESTADO_ABIERTO)
            ->getQuery();
        $cajas = $query->getResult();

        /* @var $caja Caja */
        foreach ($cajas as $caja) {
            $caja->setEstado(Caja::ESTADO_CERRADO);
            $caja->setFechaClausura(new \DateTime());
            $caja->setFechaUltimoCambio(new \DateTime());
            $user= $em->getRepository('FxSchoolBundle:Usuario')->findOneBy(array('rol'=>'administrador'));

            $caja->setSupervisorId($user);

            $cajachica=$caja->getcajachica();

            if(!is_null($cajachica)){
                $total_ingresos         =   $cajachica->getIngresos();
                $total_caja             =   bcadd('0.00', $caja->getMontoTotal(), 2);
                $total_caja_chica       =   bcadd('0.00', $cajachica->getMontoTotal(), 2);
                $total                  =   bcadd($total_caja, $total_caja_chica, 2);

                $cajachica->setIngresos(bcadd($total_ingresos,$total_caja,2));
                $cajachica->setMontoTotal($total);

                $em->persist($cajachica);
            }


            $em->persist($caja);
            $em->flush();

            $contador=$contador+1;
        }
        $logger->info(sprintf("Se han cerrado %d cajas el dia  %s.",
            $contador,
            date('d-m-Y')
        ));
    }
}
