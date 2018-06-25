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

class ArreglarRecibosEgresoCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('fx:arreglar-recibos')
            ->setDescription('Arreglar recibos');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /* @var $em EntityManager */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $cajas = $em->getRepository('FxAccountingBundle:Caja')->findAll();
        /** @var Caja $caja */
        foreach($cajas as $caja){
            $recibo_egresos=$em->getRepository('FxAccountingBundle:ReciboEgreso')->findBy(array(
                    'caja' => $caja,
                ));
                /** @var ReciboEgreso $egreso */
                foreach($recibo_egresos as $egreso){
                    $egreso->setLocal($egreso->getLocal2());
                    $output->writeln(sprintf('Se agrego a la caja %d el recibo de egreso %d', $caja->getId(), $egreso->getId()));
                    $em->persist($egreso);
                    $em->flush();
                }
        }
        $output->writeln('¡Listo!');
    }



}
