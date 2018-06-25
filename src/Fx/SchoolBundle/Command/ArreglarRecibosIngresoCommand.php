<?php

namespace Fx\SchoolBundle\Command;

use Doctrine\ORM\EntityManager;
use Fx\SchoolBundle\Entity\Estudiante;
use Fx\AccountingBundle\Entity\Caja;
use Fx\AccountingBundle\Entity\ReciboIngreso;
use Fx\AccountingBundle\Entity\ReciboEgreso;
use Fx\SchoolBundle\Utils\CuiGenerator;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ArreglarRecibosIngresoCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('fx:arreglar-recibos-ingresos')
            ->setDescription('Arreglar recibos ingresos');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /* @var $em EntityManager */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $recbios = $em->getRepository('FxAccountingBundle:ReciboIngreso')->createQueryBuilder('r')
        ->where('r.fechaRegistro BETWEEN  :fecha1 AND :fecha2')
        ->andWhere('r.codigoLocal = :local')
        ->setParameter('fecha1','2016-12-01 00:00:00')
        ->setParameter('fecha2','2016-12-14 23:59:59')
        ->setParameter('local',2)
        ->getQuery();
        /** @var ReciboIngreso $recibo */
        foreach ($recbios->getResult() as $recibo){
            $info=$recibo->getInfo();
            if($info['user_id']==28){
                $recibo->setCodigoLocal(1);
                $em->persist($recibo);
                $em->flush();
                $output->writeln($recibo->getNumeroRecibo());
            }
        }

    }



}
