<?php

namespace UJM\ExoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UJM\ExoBundle\Entity\AudioMark.
 *
 * @ORM\Entity
 * @ORM\Table(name="ujm_audiomark")
 */
class AudioMark
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="InteractionAudioMark", inversedBy="audioMarks")
     * @ORM\JoinColumn(name="interaction_audiomark_id", referencedColumnName="id")
     */
    private $interactionAudioMark;

    /**
     * @ORM\Column(type="float")
     */
    private $start;

    /**
     * @ORM\Column(type="float")
     */
    private $end;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $feedback;

    /**
     * @var bool
     *
     * @ORM\Column(name="rightAnswer", type="boolean")
     */
    private $rightAnswer;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFeedback()
    {
        return $this->feedback;
    }

    /**
     * @param $feedback
     */
    public function setFeedback($feedback)
    {
        $this->feedback = $feedback;
    }

    /**
     * @param string $start
     */
    public function setStart($start)
    {
        $this->start = $start;
    }

    /**
     * @return string
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param string $end
     */
    public function setEnd($end)
    {
        $this->end = $end;
    }

    /**
     * @return string
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @return InteractionAudioMark
     */
    public function getInteractionAudioMark()
    {
        return $this->interactionAudioMark;
    }

    /**
     * @param InteractionAudioMark $interactionAudioMark
     */
    public function setInteractionAudioMark(InteractionAudioMark $interactionAudioMark)
    {
        $this->interactionAudioMark = $interactionAudioMark;
    }

    /**
     * Set rightAnswer.
     *
     * @param bool $rightAnswer
     *
     * @return AudioMark
     */
    public function setRightAnswer($rightAnswer)
    {
        $this->rightAnswer = $rightAnswer;

        return $this;
    }

    /**
     * Get rightAnswer.
     *
     * @return bool
     */
    public function isRightAnswer()
    {
        return $this->rightAnswer;
    }
}
