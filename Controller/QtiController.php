<?php

namespace UJM\ExoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class QtiController extends Controller {

    /**
     *Import question in QTI
     *
     * @access public
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     */
    public function importAction()
    {
        $request = $this->container->get('request');
        $exoID = $request->get('exerciceID');
        
        if (strstr($_FILES["qtifile"]["type"], 'application/zip') === false) {

            return $this->importError('qti format warning');
        }

        $qtiRepos = $this->container->get('ujm.qti_repository');
        if ($this->extractFiles($qtiRepos) === false) {

            return $this->importError('qti can\'t open zip');
        }

        $scanFile = $qtiRepos->scanFiles();
        if ($scanFile !== true) {

            return $this->importError($scanFile);
        }
        
        if ($exoID == -1) {
            return $this->forward('UJMExoBundle:Question:index', array());
        } else {
            return $this->forward('UJMExoBundle:Exercise:importQuestion', array('exoID' => $exoID,'pageGoNow'=> 1, 'maxPage'=> 10, 'nbItem' => 1, 'displayAll' => 0, 'idExo'=> -1, 'QuestionsExo' => false ));
        }
    }

    /**
     * Create the form to import a QTI file
     *
     * @access public
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function importFormAction()
    {
        $request = $this->container->get('request');

        if ($request->isXmlHttpRequest()) {
            $exoID = $request->request->get('exoID');
        }
        
        return $this->render('UJMExoBundle:QTI:import.html.twig', array('exoID' => $exoID));
    }

    /**
     * Extract the QTI files
     *
     * @access private
     *
     * @param UJM\ExoBundle\Services\classes\QTI $qtiRepos
     *
     * @return boolean
     */
    private function extractFiles($qtiRepos)
    {
        $qtiRepos->createDirQTI();

        $rst = 'its a zip file';
        move_uploaded_file($_FILES["qtifile"]["tmp_name"],
                $qtiRepos->getUserDir() . $_FILES["qtifile"]["name"]);
        $zip = new \ZipArchive;
        if ($zip->open($qtiRepos->getUserDir() . $_FILES["qtifile"]["name"]) !== true) {

            return false;
        }
        $res = zip_open($qtiRepos->getUserDir() . $_FILES["qtifile"]["name"]);
        $zip->extractTo($qtiRepos->getUserDir());
        $tab_liste_fichiers = array();
        while ($zip_entry = zip_read($res)) {
            if(zip_entry_filesize($zip_entry) > 0) {
                $nom_fichier = zip_entry_name($zip_entry);
                $rst =$rst . '-_-_-_'.$nom_fichier;
                array_push($tab_liste_fichiers, $nom_fichier);

            }
        }
        $zip->close();

        return true;
    }

    /**
     * Return a response with warning
     *
     * @access private
     *
     * @return Response
     *
     */
    private function importError($mssg)
    {
        return $this->forward('UJMExoBundle:Question:index',
                    array('qtiError' =>
                        $this->get('translator')->trans($mssg))
                    );
    }

}
