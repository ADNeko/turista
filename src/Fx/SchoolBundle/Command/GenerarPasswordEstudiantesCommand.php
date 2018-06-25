<?php

namespace Fx\SchoolBundle\Command;

use Doctrine\ORM\EntityManager;
use Fx\SchoolBundle\Entity\Estudiante;
use Fx\SchoolBundle\Utils\CuiGenerator;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerarPasswordEstudiantesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('fx:generar-password-estudiantes')
            ->setDescription('Generar password de los estudiantes');
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $encoder = $this->getContainer()->get('security.password_encoder');

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $query = $em->createQueryBuilder()
            ->select('e')
            ->from('FxSchoolBundle:Estudiante', 'e')
            ->where('e.documento is not null')
            ->getQuery();

        $estudiantes = $query->getResult();

        /* @var $estudiante Estudiante */
        foreach ($estudiantes as $estudiante) {
            $estudiante->setPassword($encoder->encodePassword($estudiante, $estudiante->getDocumento()));
            $em->persist($estudiante);
        }

        $em->flush();

        $output->writeln('¡Listo!');
    }
}
