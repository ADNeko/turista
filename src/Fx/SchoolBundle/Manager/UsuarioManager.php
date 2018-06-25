<?php

namespace Fx\SchoolBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Doctrine\UserManager;
use Fx\SchoolBundle\Entity\Usuario;
use Fx\SchoolBundle\Exception\SchoolException;
use Fx\SchoolBundle\Form\Model\BuscarUsuario;
use Fx\SchoolBundle\Repository\UsuarioRepository;
use Symfony\Bridge\Monolog\Logger;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Inject;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * @Service(id="fx_school.usuario_manager")
 */
class UsuarioManager
{
    private $em;
    private $logger;
    private $userManager;
    private $tokenStorage;


    /**
     * @InjectParams({
     *     "em"              = @Inject("doctrine.orm.entity_manager"),
     *     "logger"          = @Inject("monolog.logger.school"),
     *     "userManager"     = @Inject("fos_user.user_manager"),
     *     "tokenStorage"    = @Inject("security.token_storage")
     * })
     */
    public function __construct(
        UserManager $userManager,
        EntityManagerInterface $em,
        Logger $logger,
        TokenStorage $tokenStorage
    )
    {
        $this->userManager  = $userManager;
        $this->em           = $em;
        $this->logger       = $logger;
        $this->tokenStorage = $tokenStorage;
    }


    /*
     * @return Usuario
     */
    public function newUsuario()
    {
        return $this->userManager->createUser();
    }


    public function addUsuario(Usuario $usuario)
    {
        $usuario->setEnabled(true);
        $this->setRoles($usuario, $usuario->getRol());

        if (!$usuario->getLocales()->contains($usuario->getLocalPrincipal()))
            $usuario->addLocale($usuario->getLocalPrincipal());

        $this->userManager->updateUser($usuario);
        $this->logger->log('info', 'Se creó el usuario ' . $usuario->getUsername() . '.');
    }


    public function updateUsuario(Usuario $usuario)
    {
        $this->setRoles($usuario, $usuario->getRol());


        $this->userManager->updateUser($usuario);
        $this->logger->log('info', 'Se actualizó el usuario ' . $usuario->getUsername() . '.');
    }


    public function removeUsuario(Usuario $usuario)
    {
        $this->userManager->deleteUser($usuario);
        $this->logger->log('info', 'Se eliminó el usuario ' . $usuario->getUsername() . '.');
    }


    public function setRoles(Usuario $usuario, $role)
    {
        $sfRole = 'ROLE_' . strtoupper($role);

        if ($role === 'administrador')
            $sfRole = 'ROLE_ADMIN';

        $usuario->setRoles(array('ROLE_USER', $sfRole));
    }


    public function buscarUsuarios(BuscarUsuario $buscarUsuario)
    {
        /** @var UsuarioRepository $usuarioRepository */
        $usuarioRepository = $this->em->getRepository('FxSchoolBundle:Usuario');

        $data = $buscarUsuario->getFieldAndValue();

        if ($data['field'] === 'documento') {
            return $usuarioRepository->searchByDocumentoAndRolAndLocal(
                $data['value'],
                $buscarUsuario->rol,
                $buscarUsuario->localPrincipal
            );
        } else if ($data['field'] === 'apellidoPaterno') {
            return $usuarioRepository->searchByApellidoPaternoAndRolAndLocal(
                $data['value'],
                $buscarUsuario->rol,
                $buscarUsuario->localPrincipal
            );
        }

        if (!is_null($buscarUsuario->localPrincipal || !is_null($buscarUsuario->rol)))
            return $usuarioRepository->searchByRolAndLocal(
                $buscarUsuario->rol,
                $buscarUsuario->localPrincipal
            );
    }


    public function puedeHabilitar(Usuario $user, Usuario $usuario)
    {
        if ($user->hasRole('ROLE_ADMIN')) return true;
        if ($user->hasRole('ROLE_PROMOTOR') && !$usuario->hasRole('ROLE_PROMOTOR')) return true;
        if ($user->hasRole('ROLE_DIRECTOR') && !$usuario->hasRole('ROLE_DIRECTOR')) return true;
        return false;
    }


    public function deshabilitar(Usuario $usuario)
    {
        /** @var Usuario $user */
        $user = $this->tokenStorage->getToken()->getUser();

        if (!$this->puedeHabilitar($user, $usuario))
            throw new SchoolException(sprintf("Un usuario con rol %s no puede deshabilitar un usuario con rol %s.",
                strtoupper($user->getRol()),
                strtoupper($usuario->getRol())
            ));

        $usuario->setEnabled(false);
        $this->userManager->updateUser($usuario);
    }


    public function habilitar(Usuario $usuario)
    {
        /** @var Usuario $user */
        $user = $this->tokenStorage->getToken()->getUser();

        if (!$this->puedeHabilitar($user, $usuario))
            throw new SchoolException(sprintf("Un usuario con rol %s no puede habilitar un usuario con rol %s.",
                strtoupper($user->getRol()),
                strtoupper($usuario->getRol())
            ));

        $usuario->setEnabled(true);
        $this->userManager->updateUser($usuario);
    }
}
