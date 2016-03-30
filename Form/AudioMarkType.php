<?php

namespace UJM\ExoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AudioMarkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('start', 'number',
                    array(
                        'attr' => array('data-field' => 'start'),
                        'label' => 'start',
                    )
                )
            ->add('end', 'number',
                    array(
                        'attr' => array('data-field' => 'end'),
                        'label' => 'end',
                    )
                )
            ->add(
                'rightAnswer', 'checkbox',
                array(
                    'required' => false,
                    'label' => 'right_answer',
                    'translation_domain' => 'ujm_exo',
                )
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
                array('data_class' => 'UJM\ExoBundle\Entity\AudioMark',
                      'cascade_validation' => true,
                      'translation_domain' => 'ujm_exo',
                )
        );
    }

    public function getName()
    {
        return 'ujm_exobundle_audiomarktype';
    }
}
