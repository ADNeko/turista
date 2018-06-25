<?php

namespace Fx\SchoolBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FxButtonType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('submit', 'submit', array(
                'label' => 'Click Me!',
            ));
    }


    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    }


    /**
     * @return string
     */
    public function getName()
    {
        return 'fx_school_form_fx_button';
    }
}
