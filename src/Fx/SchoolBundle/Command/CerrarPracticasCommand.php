<?php

namespace Fx\SchoolBundle\Command;

use Doctrine\ORM\EntityManager;
use Fx\AccountingBundle\Manager\CajaManager;
use Fx\SchoolBundle\Entity\Clase;
use Fx\SchoolBundle\Entity\Practica;
use Fx\SchoolBundle\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Fx\AccountingBundle\Entity\Caja;

class CerrarPracticasCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('fx:cerrar-practicas')
            ->setDescription('Cerrar practicas');
    }
    public function CerrarVencido(Clase $clase){
        if($clase->getFechaVencimientoPractica()->format('d-m-Y') <= date('d-m-Y')){
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
            ->select('p')
            ->from('FxSchoolBundle:Practica', 'p')
            ->where('p.estado = :estado')
            ->setParameter('estado',Practica::ESTADO_NUEVO)
            ->getQuery();
        $practicas = $query->getResult();

        /* @var $practica Practica */
        foreach ($practicas as $practica) {
             if($this->CerrarVencido($practica->getClase())){
                $practica->setEstado(Practica::ESTADO_CERRADO);
                $em->persist($practica);
            }
            $em->flush();
            $contador=$contador+1;
        }
        $logger->info(sprintf("Se han cerrado %d practicas el dia  %s.",
            $contador,
            date('d-m-Y')
        ));
    }
}
