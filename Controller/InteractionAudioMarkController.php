<?php

namespace UJM\ExoBundle\Controller;

use Symfony\Component\Form\FormError;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UJM\ExoBundle\Entity\InteractionAudioMark;
use UJM\ExoBundle\Entity\Response;
use UJM\ExoBundle\Form\InteractionAudioMarkType;
use UJM\ExoBundle\Form\ResponseType;
use UJM\ExoBundle\Form\InteractionAudioMarkHandler;

/**
 * InteractionOpen controller.
 */
class InteractionAudioMarkController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction()
    {
        $attr = $this->get('request')->attributes;
        $em = $this->get('doctrine')->getEntityManager();
        $vars = $attr->get('vars');

        $response = new Response();
        $audioMark = $em->getRepository('UJMExoBundle:InteractionAudioMark')->findOneByQuestion($attr->get('interaction')->getId());

        $form = $this->createForm(new ResponseType(), $response);

        $vars['interactionToDisplayed'] = $audioMark;
        $vars['form'] = $form->createView();
        $vars['exoID'] = $attr->get('exoID');

        return $this->render('UJMExoBundle:InteractionAudioMark:paper.html.twig', $vars);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction()
    {
        $attr = $this->get('request')->attributes;
        $entity = new InteractionAudioMark();
        $form = $this->createForm(
           new InteractionAudioMarkType(
               $this->container->get('security.token_storage')->getToken()->getUser()
           ), $entity
       );

        $interAudioMarkSer = $this->container->get('ujm.exo_InteractionAudioMark');
        $typeAudioMark = $interAudioMarkSer->getTypeAudioMark();

        return $this->container->get('templating')->renderResponse(
           'UJMExoBundle:InteractionAudioMark:new.html.twig', array(
           'exoID' => $attr->get('exoID'),
           'stepID' => $attr->get('stepID'),
           'entity' => $entity,
           'typeAudioMark' => json_encode($typeAudioMark),
           'form' => $form->createView(),
           )
       );
    }

    /**
     * Creates a new InteractionOpen entity.
     *
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction()
    {
        $request = $this->container->get('request');
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $services = $this->container->get('ujm.exo_InteractionQCM');
        $catSer = $this->container->get('ujm.exo_category');

        $interQCM = new InteractionAudioMark();
        $form = $this->createForm(new InteractionAudioMarkType($user), $interQCM);

        $exoID = $request->request->get('exercise');
        $stepID = $request->request->get('step');

        $exercise = $em->getRepository('UJMExoBundle:Exercise')->find($exoID);
        $step = $em->getRepository('UJMExoBundle:Step')->find($stepID);

        $formHandler = new InteractionAudioMarkHandler(
            $form, $this->get('request'), $em,
            $this->container->get('ujm.exo_exercise'), $catSer,
            $user, $exercise, $step,
            $this->get('translator')
        );

        $qcmHandler = $formHandler->processAdd();

        if ($qcmHandler === true) {
            $cat = $interQCM->getQuestion()->getCategory();
            $title = $interQCM->getQuestion()->getTitle();

            $url = ($exoID == -1)
                ? $this->generateUrl('ujm_question_index', array('categoryToFind' => base64_encode($cat), 'titleToFind' => base64_encode($title)))
                : $this->generateUrl('ujm_exercise_open', ['id' => $exoID]).'#/steps';

            return $this->redirect($url);
        }

        if ($qcmHandler == 'infoDuplicateQuestion') {
            $form->addError(new FormError(
                    $this->get('translator')->trans('info_duplicate_question', array(), 'ujm_exo')
                    ));
        }

        $typeQCM = $services->getTypeQCM();
        $formWithError = $this->render(
            'UJMExoBundle:InteractionQCM:new.html.twig', array(
                'entity' => $interQCM,
                'form' => $form->createView(),
                'error' => true,
                'exoID' => $exoID,
                'stepID' => $stepID,
                'typeQCM' => json_encode($typeQCM),
            )
        );
        $interactionType = $this->container->get('ujm.exo_question')->getTypes();
        $formWithError = substr($formWithError, strrpos($formWithError, 'GMT') + 3);

        return $this->render(
                'UJMExoBundle:Question:new.html.twig', array(
                'formWithError' => $formWithError,
                'exoID' => $exoID,
                'stepID' => $stepID,
                'linkedCategory' => $catSer->getLinkedCategories(),
                'locker' => $catSer->getLockCategory(),
                'interactionType' => $interactionType,
            )
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction()
    {
        $attr = $this->get('request')->attributes;
        $interaction = $attr->get('interaction');
        $user = $attr->get('user');
        $exoId = $attr->get('exoID');
        $catId = $attr->get('catID');

        $audioMarkService = $this->container->get('ujm.exo_InteractionOpen');
        $catService = $this->container->get('ujm.exo_category');
        $em = $this->get('doctrine')->getEntityManager();

        $audioMark = $em->getRepository('UJMExoBundle:InteractionAudioMark')->findOneByQuestion($interaction->getId());
        $catService->ctrlCategory($audioMark->getQuestion());

        $editForm = $this->createForm(
            new InteractionAudioMarkType($user, $catId), $audioMark
        );

        if ($exoId != -1) {
            $exercise = $em->getRepository('UJMExoBundle:Exercise')->find($exoId);
            $variables['_resource'] = $exercise;
        }

        $typeOpen = $audioMarkService->getTypeOpen();
        $linkedCategory = $catService->getLinkedCategories();

        $variables['entity'] = $audioMark;
        $variables['edit_form'] = $editForm->createView();
        $variables['nbResponses'] = $audioMarkService->getNbReponses($interaction);
        $variables['linkedCategory'] = $linkedCategory;
        $variables['typeOpen'] = json_encode($typeOpen);
        $variables['exoID'] = $exoId;
        $variables['locker'] = $catService->getLockCategory();

        if ($exoId != -1) {
            $exercise = $em->getRepository('UJMExoBundle:Exercise')->find($exoId);
            $variables['_resource'] = $exercise;
        }

        return $this->render('UJMExoBundle:InteractionAudioMark:edit.html.twig', $variables);
    }

    /**
     * Edits an existing InteractionOpen entity.
     *
     *
     * @param int $id id of InteractionOpen
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateAction($id)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $exoID = $this->container->get('request')->request->get('exercise');
        $catID = -1;

        $em = $this->getDoctrine()->getManager();

        $audioMark = $em->getRepository('UJMExoBundle:InteractionAudioMark')->find($id);

        if (!$audioMark) {
            throw $this->createNotFoundException('Unable to find InteractionAudioMark entity.');
        }

        if ($user->getId() != $audioMark->getQuestion()->getUser()->getId()) {
            $catID = $audioMark->getQuestion()->getCategory()->getId();
        }

        $editForm = $this->createForm(new InteractionAudioMarkType($user, $catID), $audioMark);

        $formHandler = new InteractionAudioMarkHandler(
            $editForm, $this->get('request'), $this->getDoctrine()->getManager(),
            $this->container->get('ujm.exo_exercise'), $this->container->get('ujm.exo_category'),
            $user, -1,
            $this->get('translator')
        );

        if ($formHandler->processUpdate($audioMark)) {
            $url = $exoID == -1
                ? $this->generateUrl('ujm_question_index')
                : $this->generateUrl('ujm_exercise_open', ['id' => $exoID]).'#/steps';

            return $this->redirect($url);
        }

        return $this->forward(
            'UJMExoBundle:Question:edit', array(
                'exoID' => $exoID,
                'id' => $audioMark->getQuestion()->getId(),
                'form' => $editForm,
            )
        );
    }

    /**
     * Deletes a InteractionOpen entity.
     *
     *
     * @param int    $id      id of InteractionOpen
     * @param intger $pageNow for pagination, actual page
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction($id, $pageNow)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('UJMExoBundle:InteractionOpen')->find($id);
        //Deleting of relations, if there the question is shared
        $sharesQuestion = $em->getRepository('UJMExoBundle:Share')->findBy(array('question' => $entity->getQuestion()->getId()));
        foreach ($sharesQuestion as $share) {
            $em->remove($share);
        }
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find InteractionOpen entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('ujm_question_index', array('pageNow' => $pageNow)));
    }

    /**
     * To test the open question by the teacher.
     *
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function responseOpenAction()
    {
        $vars = array();
        $request = $this->get('request');
        $postVal = $req = $request->request->all();

        if ($postVal['exoID'] != -1) {
            $exercise = $this->getDoctrine()->getManager()->getRepository('UJMExoBundle:Exercise')->find($postVal['exoID']);
            $vars['_resource'] = $exercise;
        }

        $interSer = $this->container->get('ujm.exo_InteractionOpen');
        $res = $interSer->response($request);

        $vars['interOpen'] = $res['interOpen'];
        $vars['penalty'] = $res['penalty'];
        $vars['response'] = $res['response'];
        $vars['score'] = $res['score'];
        $vars['tempMark'] = $res['tempMark'];
        $vars['exoID'] = $postVal['exoID'];

        return $this->render('UJMExoBundle:InteractionAudioMark:openOverview.html.twig', $vars);
    }
}
