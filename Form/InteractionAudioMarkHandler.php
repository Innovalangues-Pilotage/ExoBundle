<?php

namespace UJM\ExoBundle\Form;

class InteractionAudioMarkHandler extends QuestionHandler
{
    /**
     * Implements the abstract method.
     */
    public function processAdd()
    {
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
                $this->onSuccessAdd($this->form->getData());

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
     * @param \UJM\ExoBundle\Entity\InteractionOpen $originalInterOpen
     *
     * Return boolean
     */
    public function processUpdate($originalInterOpen)
    {
        $originalWrs = array();
        $originalHints = array();

        foreach ($originalInterOpen->getQuestion()->getHints() as $hint) {
            $originalHints[] = $hint;
        }

        if ($this->request->getMethod() == 'POST') {
            $this->form->handleRequest($this->request);

            if ($this->form->isValid()) {
                $this->onSuccessUpdate($this->form->getData(), $originalWrs, $originalHints);

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
}
