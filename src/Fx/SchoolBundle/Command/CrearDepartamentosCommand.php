<?php

namespace Fx\SchoolBundle\Command;

use Doctrine\ORM\EntityManager;
use Fx\SchoolBundle\Entity\Departamento;
use Fx\SchoolBundle\Entity\Local;
use Fx\SchoolBundle\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Fx\AccountingBundle\Entity\Caja;

class CrearDepartamentosCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('fx:crear-departamentos')
            ->setDescription('Configurar por primera ves los departamentos');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        /* @var $em EntityManager */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
//        $logger=$this->getContainer()->get('monolog.logger.school');
        $locales = $em->getRepository('FxSchoolBundle:Local')->findAll();
        /** @var Local $local */
        foreach($locales as $local){
                $departamento = new Departamento($local);
                $departamento->setNombre(Departamento::DEPARTAMENTO_ADMINISTRACION);
                $usuarios=$em->getRepository('FxSchoolBundle:Usuario')->findBy(array(
                   'rol' => Usuario::ROL_ADMINISTRADOR
                ));
                $em->persist($departamento);
            /** @var Usuario $usuario */
            foreach($usuarios as $usuario){
                    $usuario->addDepartamento($departamento);
                    $departamento->addUsuario($usuario);
                    $em->persist($departamento);
                    $em->persist($usuario);
                    $em->flush();
                    $output->writeln($usuario->getNombreCompleto().'-'.$departamento->getLocal().'-'.'Asignado');
                }
        }
    }
}
