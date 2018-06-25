<?php

namespace Fx\SchoolBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class EstudianteAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('codigo')
            ->add('password')
            ->add('email')
            ->add('apellidoPaterno')
            ->add('apellidoMaterno')
            ->add('nombres')
            ->add('tipoDocumento')
            ->add('documento')
            ->add('fechaNacimiento')
            ->add('sexo')
            ->add('estadoCivil')
            ->add('grado')
            ->add('pais')
            ->add('departamento')
            ->add('provincia')
            ->add('distrito')
            ->add('fechaIngreso')
            ->add('fechaInicio')
            ->add('direccionDni')
            ->add('direccionReal')
            ->add('celular1')
            ->add('celular2')
            ->add('telefono')
            ->add('trabajo')
            ->add('ocupacion')
            ->add('observacion')
            ->add('estado');
    }


    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('codigo')
            ->add('password')
            ->add('email')
            ->add('apellidoPaterno')
            ->add('apellidoMaterno')
            ->add('nombres')
            ->add('tipoDocumento')
            ->add('documento')
            ->add('fechaNacimiento')
            ->add('sexo')
            ->add('estadoCivil')
            ->add('grado')
            ->add('pais')
            ->add('departamento')
            ->add('provincia')
            ->add('distrito')
            ->add('fechaIngreso')
            ->add('fechaInicio')
            ->add('direccionDni')
            ->add('direccionReal')
            ->add('celular1')
            ->add('celular2')
            ->add('telefono')
            ->add('trabajo')
            ->add('ocupacion')
            ->add('observacion')
            ->add('estado')
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
            ->add('id')
            ->add('codigo')
            ->add('password')
            ->add('email')
            ->add('apellidoPaterno')
            ->add('apellidoMaterno')
            ->add('nombres')
            ->add('tipoDocumento')
            ->add('documento')
            ->add('fechaNacimiento')
            ->add('sexo')
            ->add('estadoCivil')
            ->add('grado')
            ->add('pais')
            ->add('departamento')
            ->add('provincia')
            ->add('distrito')
            ->add('fechaIngreso')
            ->add('fechaInicio')
            ->add('direccionDni')
            ->add('direccionReal')
            ->add('celular1')
            ->add('celular2')
            ->add('telefono')
            ->add('trabajo')
            ->add('ocupacion')
            ->add('observacion')
            ->add('estado');
    }


    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('codigo')
            ->add('password')
            ->add('email')
            ->add('apellidoPaterno')
            ->add('apellidoMaterno')
            ->add('nombres')
            ->add('tipoDocumento')
            ->add('documento')
            ->add('fechaNacimiento')
            ->add('sexo')
            ->add('estadoCivil')
            ->add('grado')
            ->add('pais')
            ->add('departamento')
            ->add('provincia')
            ->add('distrito')
            ->add('fechaIngreso')
            ->add('fechaInicio')
            ->add('direccionDni')
            ->add('direccionReal')
            ->add('celular1')
            ->add('celular2')
            ->add('telefono')
            ->add('trabajo')
            ->add('ocupacion')
            ->add('observacion')
            ->add('estado');
    }
}
