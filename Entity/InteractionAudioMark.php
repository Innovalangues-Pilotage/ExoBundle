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
     * @ORM\OneToMany(
     *     targetEntity="AudioMark",
     *     mappedBy="interactionAudioMark",
     *     cascade={"persist"}
     * )
     */
    public $audioMarks;

    /**
     * Constructs a new instance of choices.
     */
    public function __construct()
    {
        $this->audioMarks = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getAudioMarks()
    {
        return $this->audioMarks;
    }

    /**
     * @param AudioMark $audioMark
     */
    public function addAudioMark(AudioMark $audioMark)
    {
        $this->audioMarks->add($audioMark);
        $audioMark->setInteractionAudioMark($this);
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
}
