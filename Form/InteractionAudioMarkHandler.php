<?php

namespace UJM\ExoBundle\Form;

use Doctrine\Common\Collections\ArrayCollection;

class InteractionAudioMarkHandler extends QuestionHandler
{
    /**
     * Implements the abstract method.
     */
    public function processAdd()
    {
        $interaction = $this->form->getData();

        $audioMarks = new ArrayCollection();
        foreach ($interaction->getAudioMarks() as $audioMark) {
            $audioMarks->add($audioMark);
        }

        if ($this->request->getMethod() == 'POST') {
            $this->form->handleRequest($this->request);

            //Uses the default category if no category selected
            $this->checkCategory();
            //If title null, uses the first 50 characters of "invite" (enuncicate)
            $this->checkTitle();
            if ($this->validateNbClone() === false) {
                return 'infoDuplicateQuestion';
            }
            if ($this->form->isValid()) {
                $this->onSuccessAdd($interaction);
                $this->updateAudioMarks($audioMarks, $interaction);

                return true;
            }
        }

        return false;
    }

    /**
     * Implements the abstract method.
     *
     *
     * @param \UJM\ExoBundle\Entity\InteractionOpen $interOpen
     */
    protected function onSuccessAdd($interOpen)
    {
        $interOpen->getQuestion()->setDateCreate(new \Datetime());
        $interOpen->getQuestion()->setUser($this->user);

        $this->em->persist($interOpen);
        $this->em->persist($interOpen->getQuestion());

        $this->persistHints($interOpen);

        $this->em->flush();

        $this->addAnExercise($interOpen);

        $this->duplicateInter($interOpen);
    }

    /**
     * Implements the abstract method.
     *
     *
     * @param \UJM\ExoBundle\Entity\InteractionOpen $interaction
     *
     * Return boolean
     */
    public function processUpdate($interaction)
    {
        $originalWrs = array();
        $originalHints = array();

        $audioMarks = new ArrayCollection();
        foreach ($interaction->getAudioMarks() as $audioMark) {
            $audioMarks->add($audioMark);
        }

        foreach ($interaction->getQuestion()->getHints() as $hint) {
            $originalHints[] = $hint;
        }

        if ($this->request->getMethod() == 'POST') {
            $this->form->handleRequest($this->request);

            if ($this->form->isValid()) {
                $this->onSuccessUpdate($this->form->getData(), $originalWrs, $originalHints);
                $this->updateAudioMarks($audioMarks, $interaction);

                return true;
            }
        }

        return false;
    }

    /**
     * Implements the abstract method.
     */
    protected function onSuccessUpdate()
    {
        $arg_list = func_get_args();
        $interOpen = $arg_list[0];
        $originalWrs = $arg_list[1];
        $originalHints = $arg_list[2];

        $this->modifyHints($interOpen, $originalHints);

        $this->em->persist($interOpen);
        $this->em->persist($interOpen->getQuestion());

        $this->em->flush();
    }

    private function updateAudioMarks($audioMarks, $interaction)
    {
        // remove unused audiomarks
        foreach ($audioMarks as $audioMark) {
            if ($interaction->getAudioMarks()->contains($audioMark) === false) {
                $this->em->remove($audioMark);
            }
        }

        // link audiomarks to interaction
        foreach ($interaction->getAudioMarks() as $audioMark) {
            $audioMark->setInteractionAudioMark($interaction);
            $this->em->persist($audioMark);
        }
        $this->em->flush();

        return;
    }
}
