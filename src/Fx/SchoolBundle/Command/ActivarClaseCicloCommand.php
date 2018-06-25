<?php

namespace Fx\SchoolBundle\Command;

use Doctrine\ORM\EntityManager;
use Fx\AccountingBundle\Entity\BoletaVenta;
use Fx\SchoolBundle\Entity\Ciclo;
use Fx\SchoolBundle\Entity\Clase;
use Fx\SchoolBundle\Entity\Curso;
use Fx\SchoolBundle\Entity\Estudiante;
use Fx\AccountingBundle\Entity\Caja;
use Fx\AccountingBundle\Entity\ReciboIngreso;
use Fx\AccountingBundle\Entity\ReciboEgreso;
use Fx\SchoolBundle\Entity\Modulo;
use Fx\SchoolBundle\Utils\CuiGenerator;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ActivarClaseCicloCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('fx:arreglar-ciclo-id')
            ->setDescription('Arreglar caja por ciclo modulo')
            ->addArgument(
                'id_ciclo',
                InputArgument::OPTIONAL,
                'ciclo_id'
            )
            ->addArgument(
                'id_modulo',
                InputArgument::OPTIONAL,
                'modulo_id'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $ciclo_id = $input->getArgument('id_ciclo');
        $output->writeln('El ciclo seleccionado es ' . $ciclo_id);
        $modulo_id = $input->getArgument('id_modulo');
        $output->writeln('EL modulo seleccionado es ' . $modulo_id);
        $clases=array();
        /* @var $em EntityManager */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        /** @var Modulo $modulo */
        $modulo = $em->getRepository('FxSchoolBundle:Modulo')->findOneBy(array(
            'id' => $modulo_id
        ));
        /** @var Ciclo $ciclo */
        $ciclo = $em->getRepository('FxSchoolBundle:Ciclo')->findOneBy(array(
            'id' => $ciclo_id
        ));
        $output->writeln($modulo->getNombre());
        $output->writeln($ciclo->getLocal()->getNombre());
        /** @var Curso $curso */
        foreach ($modulo->getCursos() as $curso){
            /** @var Clase $clase */
            $clase= $em->getRepository('FxSchoolBundle:Clase')->findOneBy(array(
                'ciclo'=>$ciclo,
                'curso' => $curso
            ));
            $clases[]=$clase;
        }
        $output->writeln(count($clases));
        /** @var Clase $clase */
        foreach ($clases as $clase) {
            $output->writeln($clase->getCurso()->getNombre());
            $clase->setEstado(Clase::ESTADO_NUEVO);
            $em->persist($clase);
            $em->flush();
        }
        $output->writeln('¡Listo!');
    }


}
