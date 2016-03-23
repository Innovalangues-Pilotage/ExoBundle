<?php

namespace UJM\ExoBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="ujm_interaction_audio_mark")
 */
class InteractionAudioMark extends AbstractInteraction
{
    const TYPE = 'InteractionAudioMark';

    /**
     * @ORM\ManyToOne(targetEntity="TypeAudioMark")
     * @ORM\JoinColumn(name="type_audio_mark_id", referencedColumnName="id")
     */
    private $typeAudioMark;

    /**
     * @ORM\ManyToOne(targetEntity="Claroline\CoreBundle\Entity\Resource\ResourceNode")
     * @ORM\JoinColumn(onDelete="SET NULL", nullable=true)
     */
    protected $audioResource;

    /**
     * Constructs a new instance of choices.
     */
    public function __construct()
    {
        $this->choices = new ArrayCollection();
    }

    /**
     * Get resource node.
     *
     * @return string
     */
    public function getAudioResource()
    {
        return $this->audioResource;
    }

    /**
     * Set resource node.
     *
     * @param ResourceNode $primaryResource
     *
     * @return activity
     */
    public function setAudioResource($audioResource = null)
    {
        $this->audioResource = $audioResource;

        return $this;
    }

    /**
     * @return string
     */
    public static function getQuestionType()
    {
        return self::TYPE;
    }

    /**
     * @return TypeQCM
     */
    public function getTypeAudioMark()
    {
        return $this->typeAudioMark;
    }

    /**
     * @param TypeQCM $typeQCM
     */
    public function setTypeAudioMark(TypeAudioMark $typeAudioMark)
    {
        $this->typeAudioMark = $typeAudioMark;
    }

    /**
     * @param bool $shuffle
     */
    public function setShuffle($shuffle)
    {
        $this->shuffle = $shuffle;
    }

    /**
     * @return bool
     */
    public function getShuffle()
    {
        return $this->shuffle;
    }

    /**
     * @param float $scoreRightResponse
     */
    public function setScoreRightResponse($scoreRightResponse)
    {
        $this->scoreRightResponse = $scoreRightResponse;
    }

    /**
     * @return float
     */
    public function getScoreRightResponse()
    {
        return $this->scoreRightResponse;
    }

    /**
     * @param float $scoreFalseResponse
     */
    public function setScoreFalseResponse($scoreFalseResponse)
    {
        $this->scoreFalseResponse = $scoreFalseResponse;
    }

    /**
     * @return float
     */
    public function getScoreFalseResponse()
    {
        return $this->scoreFalseResponse;
    }

    /**
     * @param bool $weightResponse
     */
    public function setWeightResponse($weightResponse)
    {
        $this->weightResponse = $weightResponse;
    }

    /**
     * @return bool
     */
    public function getWeightResponse()
    {
        return $this->weightResponse;
    }

    /**
     * @return ArrayCollection
     */
    public function setChoices(ArrayCollection $choices)
    {
        $this->choices = $choices;

        return $this->choices;
    }

    /**
     * @return ArrayCollection
     */
    public function getChoices()
    {
        return $this->choices;
    }

    /**
     * @param Choice $choice
     */
    public function addChoice(Choice $choice)
    {
        $this->choices->add($choice);
        $choice->setInteractionQCM($this);
    }

    public function shuffleChoices()
    {
        $this->sortChoices();
        $i = 0;
        $tabShuffle = [];
        $tabFixed = [];
        $choices = new ArrayCollection();
        $choiceCount = count($this->choices);

        while ($i < $choiceCount) {
            if ($this->choices[$i]->getPositionForce() === false) {
                $tabShuffle[$i] = $i;
                $tabFixed[] = -1;
            } else {
                $tabFixed[] = $i;
            }

            ++$i;
        }

        shuffle($tabShuffle);

        $i = 0;
        $choiceCount = count($this->choices);

        while ($i < $choiceCount) {
            if ($tabFixed[$i] != -1) {
                $choices[] = $this->choices[$i];
            } else {
                $index = $tabShuffle[0];
                $choices[] = $this->choices[$index];
                unset($tabShuffle[0]);
                $tabShuffle = array_merge($tabShuffle);
            }

            ++$i;
        }

        $this->choices = $choices;
    }

    public function sortChoices()
    {
        $tab = [];
        $choices = new ArrayCollection();

        foreach ($this->choices as $choice) {
            $tab[] = $choice->getOrdre();
        }

        asort($tab);

        foreach (array_keys($tab) as $indice) {
            $choices[] = $this->choices[$indice];
        }

        $this->choices = $choices;
    }

    public function __clone()
    {
        if ($this->id) {
            $this->id = null;
            $this->question = clone $this->question;
            $newChoices = new ArrayCollection();

            foreach ($this->choices as $choice) {
                $newChoice = clone $choice;
                $newChoice->setInteractionQCM($this);
                $newChoices->add($newChoice);
            }

            $this->choices = $newChoices;
        }
    }
}
