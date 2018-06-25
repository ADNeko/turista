<?php

namespace Fx\SchoolBundle\Form;

use Fx\SchoolBundle\Entity\Usuario;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BuscarUsuarioType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('apellidoPaterno', 'text', array(
                'required' => false,
                'label'    => "Apellido paterno",
                'attr'     => array(
                    'placeholder' => "Visa"
                ),
            ))
            ->add('rol', 'choice', array(
                'choices'  => array(
                    Usuario::ROL_ADMISION    => 'VIsitante',
                    Usuario::ROL_ADMINISTRADOR => 'Administrador',
                ),
                'label'    => "Rol",
                'required' => false,
            ));
    }


    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Fx\SchoolBundle\Form\Model\BuscarUsuario'
        ));
    }


    /**
     * @return string
     */
    public function getName()
    {
        return 'fx_school_form_buscar_usuario';
    }
}
