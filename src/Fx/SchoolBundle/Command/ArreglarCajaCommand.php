<?php

namespace Fx\SchoolBundle\Command;

use Doctrine\ORM\EntityManager;
use Fx\AccountingBundle\Entity\BoletaVenta;
use Fx\SchoolBundle\Entity\Estudiante;
use Fx\AccountingBundle\Entity\Caja;
use Fx\AccountingBundle\Entity\ReciboIngreso;
use Fx\AccountingBundle\Entity\ReciboEgreso;
use Fx\SchoolBundle\Utils\CuiGenerator;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ArreglarCajaCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('fx:arreglar-caja-id')
            ->setDescription('Arreglar caja por id')
            ->addArgument(
                'id',
                InputArgument::OPTIONAL,
                '¿que caja quiere arreglar?'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $id = $input->getArgument('id');
        $output->writeln('La caja seleccionada es ' . $id);

        /* @var $em EntityManager */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        /** @var Caja $caja */
        $caja = $em->getRepository('FxAccountingBundle:Caja')->findOneBy(array(
            'id' => $id
        ));
        $total_egresos= '0.00';
        $total_ingreso= '0.00';

                $recibo_ingresos=$em->getRepository('FxAccountingBundle:ReciboIngreso')->findBy(array(
                    'caja' => $caja,
                    'estado' => ReciboIngreso::ESTADO_OK
                ));
                $recibo_egresos=$em->getRepository('FxAccountingBundle:ReciboEgreso')->findBy(array(
                    'caja' => $caja,
                    'estado' => ReciboEgreso::ESTADO_OK
                ));

                $boleta_venta=$em->getRepository('FxAccountingBundle:BoletaVenta')->findBy(array(
                    'caja' => $caja,
                    'estado' => ReciboIngreso::ESTADO_OK
                ));
                /** @var BoletaVenta $ingreso */
                foreach($boleta_venta as $ingreso){
                    $pagadoR = bcadd('0.00', $ingreso->getMontoTotal(), 2);
                    $total_ingreso     = bcadd($total_ingreso, $pagadoR, 2);
                    $caja->addBoletasVenta($ingreso);
                    $output->writeln(sprintf('Se agrego a la caja %d la boleta %d', $caja->getId(), $ingreso->getId()));
                    $em->persist($caja);
                }
                /** @var ReciboIngreso $ingreso */
                foreach($recibo_ingresos as $ingreso){
                    $pagadoR = bcadd('0.00', $ingreso->getMontoTotal(), 2);
                    $total_ingreso     = bcadd($total_ingreso, $pagadoR, 2);
                    $caja->addRecibosIngreso($ingreso);
                    $output->writeln(sprintf('Se agrego a la caja %d el recibo ingreso %d', $caja->getId(), $ingreso->getId()));
                    $em->persist($caja);
                }
                /** @var ReciboEgreso $egreso */
                foreach($recibo_egresos as $egreso){
                    $pagado = bcadd('0.00', $egreso->getMontoTotal(), 2);
                    $total_egresos     = bcadd($total_egresos, $pagado, 2);
                    $caja->addRecibosEgreso($egreso);
                    $output->writeln(sprintf('Se agrego a la caja %d el recibo de egreso %d', $caja->getId(), $egreso->getId()));
                    $em->persist($caja);
                }
                $output->writeln(sprintf('Total de ingresos %d total de egresos %d', $total_ingreso, $total_egresos));
                $caja->setTotalEgresos($total_egresos);
                $caja->setTotalIngresos($total_ingreso);
                $caja->setMontoTotal(bcsub($total_ingreso, $total_egresos, 2));
                $output->writeln(sprintf('Total en caja %d', $caja->getMontoTotal()));
                $em->persist($caja);
                $output->writeln('¡Listo!');
                $em->flush();
            }


}
