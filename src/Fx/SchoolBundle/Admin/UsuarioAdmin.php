<?php

namespace Fx\SchoolBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class UsuarioAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('username')
            ->add('usernameCanonical')
            ->add('email')
            ->add('emailCanonical')
            ->add('enabled')
            ->add('salt')
            ->add('password')
            ->add('lastLogin')
            ->add('locked')
            ->add('expired')
            ->add('expiresAt')
            ->add('confirmationToken')
            ->add('passwordRequestedAt')
            ->add('roles')
            ->add('credentialsExpired')
            ->add('credentialsExpireAt')
            ->add('id')
            ->add('apellidoPaterno')
            ->add('apellidoMaterno')
            ->add('nombres')
            ->add('tipoDocumento')
            ->add('documento')
            ->add('fechaNacimiento')
            ->add('sexo')
            ->add('estadoCivil')
            ->add('celular')
            ->add('telefono')
            ->add('rol')
            ->add('locales');
    }


    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('username')
            ->add('usernameCanonical')
            ->add('email')
            ->add('emailCanonical')
            ->add('enabled')
            ->add('salt')
            ->add('password')
            ->add('lastLogin')
            ->add('locked')
            ->add('expired')
            ->add('expiresAt')
            ->add('confirmationToken')
            ->add('passwordRequestedAt')
            ->add('roles')
            ->add('credentialsExpired')
            ->add('credentialsExpireAt')
            ->add('id')
            ->add('apellidoPaterno')
            ->add('apellidoMaterno')
            ->add('nombres')
            ->add('tipoDocumento')
            ->add('documento')
            ->add('fechaNacimiento')
            ->add('sexo')
            ->add('estadoCivil')
            ->add('celular')
            ->add('telefono')
            ->add('rol')
            ->add('locales')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show'   => array(),
                    'edit'   => array(),
                    'delete' => array(),
                )
            ));
    }


    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('username')
            ->add('usernameCanonical')
            ->add('email')
            ->add('emailCanonical')
            ->add('enabled')
            ->add('salt')
            ->add('password')
            ->add('lastLogin')
            ->add('locked')
            ->add('expired')
            ->add('expiresAt')
            ->add('confirmationToken')
            ->add('passwordRequestedAt')
            ->add('roles')
            ->add('credentialsExpired')
            ->add('credentialsExpireAt')
            ->add('id')
            ->add('apellidoPaterno')
            ->add('apellidoMaterno')
            ->add('nombres')
            ->add('tipoDocumento')
            ->add('documento')
            ->add('fechaNacimiento')
            ->add('sexo')
            ->add('estadoCivil')
            ->add('celular')
            ->add('telefono')
            ->add('rol')
            ->add('locales');
    }


    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('username')
            ->add('usernameCanonical')
            ->add('email')
            ->add('emailCanonical')
            ->add('enabled')
            ->add('salt')
            ->add('password')
            ->add('lastLogin')
            ->add('locked')
            ->add('expired')
            ->add('expiresAt')
            ->add('confirmationToken')
            ->add('passwordRequestedAt')
            ->add('roles')
            ->add('credentialsExpired')
            ->add('credentialsExpireAt')
            ->add('id')
            ->add('apellidoPaterno')
            ->add('apellidoMaterno')
            ->add('nombres')
            ->add('tipoDocumento')
            ->add('documento')
            ->add('fechaNacimiento')
            ->add('sexo')
            ->add('estadoCivil')
            ->add('celular')
            ->add('telefono')
            ->add('rol')
            ->add('locales');
    }
}
