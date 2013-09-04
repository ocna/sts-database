<?php
use STS\Web\Controller\SecureBaseController;
use STS\Core;

class Admin_AreaController extends SecureBaseController
{
    protected $locationFacade;

    public function init()
    {
        parent::init();
        $core = Core::getDefaultInstance();
        $this->locationFacade = $core->load('LocationFacade');
    }

    /**
     * newAction
     *
     * Create a new Area
     */
    public function newAction()
    {
        // get our form
        $this->view->form = $this->getForm();
        $form = $this->getForm();
        $form->setAction('/admin/area/new');

        $request = $this->getRequest();
        if ($this->getRequest()->isPost()) {


            $postData = $request->getPost();
            if ($form->isValid($postData)) {
                die('oam 31 - AreaController');
                try {
                    $this->saveSchool($postData);
                    $this
                        ->setFlashMessageAndRedirect("The new school: \"{$postData['name']}\" has been created!", 'success', array(
                            'module' => 'admin', 'controller' => 'school', 'action' => 'index'
                        ));
                } catch (ApiException $e) {
                    $this->setFlashMessageAndUpdateLayout('An error occured while saving this information: ' . $e->getMessage(), 'error');
                }
            } else {
                $this
                    ->setFlashMessageAndUpdateLayout('It looks like you missed some information, please make the corrections below.', 'error');
            }
        }
        $this->view->form = $form;
    }

    /**
     * editAction
     *
     * Not implemented
     */
    public function editAction()
    {
        $this->view->layout()->pageHeader = $this->view->partial('partials/page-header.phtml', array(
        'title' => 'Edit Area: ' . $fullName
        ));
    }

    public function deleteAction()
    {
    }

    public function getForm()
    {
        $statesArray = array_merge(array(''), $this->locationFacade->getStates());
        $regionsArray = array_map(
            function($elt) {
                return $elt->getName();
            },
            $this->locationFacade->getAllRegions()
        );

        $regionsArray = array_combine($regionsArray, $regionsArray);
        array_unshift($regionsArray, '');

        $form = new \Admin_Area(
                        array(
                            'states' => $statesArray,
                            'regions' => $regionsArray
                        ));
        return $form;
    }
}
