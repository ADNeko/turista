<?php

namespace Fx\SchoolBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Fx\SchoolBundle\Entity\Usuario;
use Fx\AccountingBundle\Entity\Caja;
/**
 * UsuarioRepository
 */
class UsuarioRepository extends EntityRepository
{
    public function searchByApellidoPaternoAndRolAndLocal($apellidoPaterno, $rol, $localPrincipal)
    {
        $qb = $this->createQueryBuilder('u')
            ->where('u.apellidoPaterno LIKE :apellidoPaterno')
            ->setParameter('apellidoPaterno', $apellidoPaterno . '%');

        if (false === is_null($rol)) {
            $qb->andWhere('u.rol = :rol');
            $qb->setParameter('rol', $rol);
        }

        if (false === is_null($localPrincipal)) {
            $qb->andWhere('u.localPrincipal = :localPrincipal');
            $qb->setParameter('localPrincipal', $localPrincipal);
        }

        $query = $qb->getQuery();

        return $query->getResult();
    }


    public function searchByDocumentoAndRolAndLocal($documento, $rol, $localPrincipal)
    {
        $qb = $this->createQueryBuilder('u')
            ->where('u.documento = :documento')
            ->setParameter('documento', $documento);

        if (false === is_null($rol)) {
            $qb->andWhere('u.rol = :rol');
            $qb->setParameter('rol', $rol);
        }

        if (false === is_null($localPrincipal)) {
            $qb->andWhere('u.localPrincipal = :localPrincipal');
            $qb->setParameter('localPrincipal', $localPrincipal);
        }

        $query = $qb->getQuery();

        return $query->getResult();
    }


    public function searchByRolAndLocal($rol, $localPrincipal)
    {
        $qb = $this->createQueryBuilder('u');

        if (false === is_null($rol)) {
            $qb->andWhere('u.rol = :rol');
            $qb->setParameter('rol', $rol);
        }

        if (false === is_null($localPrincipal)) {
            $qb->andWhere('u.localPrincipal = :localPrincipal');
            $qb->setParameter('localPrincipal', $localPrincipal);
        }

        $query = $qb->getQuery();

        return $query->getResult();
    }


}
