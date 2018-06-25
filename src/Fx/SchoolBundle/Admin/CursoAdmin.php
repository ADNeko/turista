<?php

namespace Fx\SchoolBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class CursoAdmin extends Admin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('nombre', 'text', array('label' => 'Nombre del curso'))
            ->add('numeroNotas', 'number', array('label' => 'Número de notas'))
            ->add('nombreNotas')
            ->add('modulo', 'entity', array('class' => 'Fx\SchoolBundle\Entity\Modulo'));
    }


    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('nombre')
            ->add('numeroNotas');
    }


    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('nombre')
            ->add('numeroNotas');
    }
}