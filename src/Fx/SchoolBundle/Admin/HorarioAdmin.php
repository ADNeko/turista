<?php

namespace Fx\SchoolBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class HorarioAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('diaInicio')
            ->add('diaFinal')
            ->add('horaInicio')
            ->add('horaFinal')
            ->add('turno');
    }


    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('diaInicio')
            ->add('diaFinal')
            ->add('horaInicio')
            ->add('horaFinal')
            ->add('turno')
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
            ->add('diaInicio')
            ->add('diaFinal')
            ->add('horaInicio')
            ->add('horaFinal')
            ->add('turno');
    }


    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('diaInicio')
            ->add('diaFinal')
            ->add('horaInicio')
            ->add('horaFinal')
            ->add('turno');
    }
}
