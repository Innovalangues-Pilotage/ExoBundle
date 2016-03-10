<?php

namespace UJM\ExoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Claroline\CoreBundle\Entity\User;

class InteractionAudioMarkType extends AbstractType
{
    private $user;
    private $catID;

    public function __construct(User $user, $catID = -1)
    {
        $this->user = $user;
        $this->catID = $catID;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('question', new QuestionType($this->user, $this->catID));
        $builder
            ->add(
                'typeaudiomark', 'entity', array(
                    'class' => 'UJM\\ExoBundle\\Entity\\TypeAudioMark',
                    'label' => 'type_question',
                    'choice_translation_domain' => true,
                )
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'UJM\ExoBundle\Entity\InteractionAudioMark',
                'cascade_validation' => true,
                'translation_domain' => 'ujm_exo',
            )
        );
    }

    public function getName()
    {
        return 'ujm_exobundle_interactionaudiomarktype';
    }
}
