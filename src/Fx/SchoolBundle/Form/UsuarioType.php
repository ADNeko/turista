<?php

namespace Fx\SchoolBundle\Form;

use Fx\SchoolBundle\Entity\Usuario;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UsuarioType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('apellidoPaterno', 'text', array(
                'label'    => "Apellido paterno",
                'attr'     => array(
                    'placeholder' => "Visa",
                ),
                'required' => true,
            ))
            ->add('apellidoMaterno', 'text', array(
                'label'    => "Apellido materno",
                'attr'     => array(
                    'placeholder' => "Flores",
                ),
                'required' => false,
            ))
            ->add('nombres', 'text', array(
                'label'    => "Nombres",
                'attr'     => array(
                    'placeholder' => "Alberto"
                ),
                'required' => true,
            ))
//            ->add('plainPassword', 'repeated', array(
//                'type'            => 'password',
//                'options'         => array(
//                    'translation_domain' => 'FOSUserBundle'
//                ),
//                'first_options'   => array(
//                    'label'    => 'form.password',
//                    'attr'     => array(
//                        'placeholder' => '•••••••••',
//                    ),
//                    'required' => true,
//                ),
//                'second_options'  => array(
//                    'label'    => 'form.password_confirmation',
//                    'attr'     => array(
//                        'placeholder' => '•••••••••',
//                    ),
//                    'required' => true,
//                ),
//                'invalid_message' => 'fos_user.password.mismatch',
//            ))
            ->add('rol', 'choice', array(
                'choices'  => array(
                    Usuario::ROL_VISITANTE    => 'VISITANTE',
                    Usuario::ROL_ADMINISTRADOR => 'Administrador',
                ),
                'required' => true,
                'label'    => "Rol",
            ))
            ->add('email', 'email', array(
                'label'              => "Correo electrónico",
                'attr'               => array(
                    'placeholder' => "alberto.visa@escomape.com"
                ),
                'translation_domain' => 'FOSUserBundle',
            ));

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $usuario = $event->getData();
            $form    = $event->getForm();

            if (!$usuario || null === $usuario->getId()) {
                $form
                    ->add('username', 'text', array(
                        'label'              => 'form.username',
                        'translation_domain' => 'FOSUserBundle',
                        'required'           => true,
                    ))
                    ->add('plainPassword', 'repeated', array(
                        'type'            => 'password',
                        'options'         => array(
                            'translation_domain' => 'FOSUserBundle'
                        ),
                        'first_options'   => array(
                            'label'    => 'form.password',
                            'attr'     => array(
                                'placeholder' => '•••••••••',
                            ),
                            'required' => true,
                        ),
                        'second_options'  => array(
                            'label'    => 'form.password_confirmation',
                            'attr'     => array(
                                'placeholder' => '•••••••••',
                            ),
                            'required' => true,
                        ),
                        'invalid_message' => 'fos_user.password.mismatch',
                    ));
            } else {
                $form
                    ->add('username', 'text', array(
                        'label'              => 'form.username',
                        'translation_domain' => 'FOSUserBundle',
                        'required'           => false,
                        'disabled'           => true,
                    ));
            }
        });
    }


    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Fx\SchoolBundle\Entity\Usuario'
        ));
    }


    /**
     * @return string
     */
    public function getName()
    {
        return 'fx_school_form_usuario';
    }
}
