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
            ->add('start', 'hidden',
                    array(
                        'attr' => array('data-field' => 'start'),
                        'label' => 'audio_mark_start',
                    )
                )
            ->add('end', 'hidden',
                    array(
                        'attr' => array('data-field' => 'end'),
                        'label' => 'audio_mark_end',
                    )
                )
            ->add('feedback', 'text',
                    array(
                        'required' => false,
                        'attr' => array('data-field' => 'feedback', 'class' => 'input-sm'),
                        'label' => 'audio_mark_feedback',
                    )
                )
            ->add('leftTolerancy', 'integer',
                    array(
                        'required' => false,
                        'attr' => array('data-field' => 'leftTolerancy', 'class' => 'input-sm', 'step' => '100'),
                        'label' => 'audio_mark_leftTolerancy',
                        'empty_data' => 0,
                    )
                )
            ->add('rightTolerancy', 'integer',
                    array(
                        'required' => false,
                        'attr' => array('data-field' => 'rightTolerancy', 'class' => 'input-sm', 'step' => '100'),
                        'label' => 'audio_mark_rightTolerancy',
                        'empty_data' => 0,
                    )
                )
            ->add(
                'rightAnswer', 'checkbox',
                array(
                    'required' => false,
                    'label' => 'audio_mark_right_answer',
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
