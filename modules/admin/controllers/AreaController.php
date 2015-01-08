<?php
use STS\Web\Controller\SecureBaseController;
use STS\Core;
use STS\Core\Location\RegionDto;

class Admin_AreaController extends SecureBaseController
{
    protected $locationFacade;

    /**
     * init
     */
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
        $form = $this->getForm();
        $form->setAction('/admin/area/new');

        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getPost();
            if ($form->isValid($postData)) {
                try {
                    $this->saveArea($postData);
                    $this->setFlashMessageAndRedirect(
                        "The new area: \"{$postData['name']}\" has been created!",
                        'success',
                        array('module' => 'admin', 'controller' => 'region', 'action' => 'index'));
                } catch (ApiException $e) {
                    $this->setFlashMessageAndUpdateLayout('An error occurred while saving this information: ' . $e->getMessage(), 'error');
                }
            } else {
                $this->setFlashMessageAndUpdateLayout('It looks like you missed some information, please make the corrections below.', 'error');
            }
        }

        // display the form
        $this->view->form = $form;
    }

    /**
     * editAction
     */
    public function editAction()
    {
        // get our area
        $id = $this->getRequest()->getParam('id');
        $dto = $this->locationFacade->getAreaById($id);

        $this->view->layout()->pageHeader = $this->view->partial(
            'partials/page-header.phtml',
            array(
                'title' => 'Edit Area: ' . $dto->getName(),
            )
        );

        // get our form
        $form = $this->getForm();
        $form->setAction('/admin/area/edit?id=' . $id);

        $form->populate(array(
            'name' => $dto->getName(),
            'city' => $dto->getCity(),
            'state' => $dto->getState(),
            'region' => $dto->getRegionName(),
        ));

        // process updates
        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getPost();
            if ($form->isValid($postData)) {
                try {
                    $updatedArea = $this->updateArea($id, $postData);
                    $this->setFlashMessageAndRedirect(
                        "The area: \"{$updatedArea->getName()}\" has been updated!",
                        'success',
                        array(
                            'module' => 'admin',
                            'controller' => 'region',
                            'action' => 'index',
                        )
                    );
                } catch (ApiException $e) {
                    $this->setFlashMessageAndUpdateLayout('An error occurred while saving this information: ' . $e->getMessage(), 'error');
                }
            } else {
                $this->setFlashMessageAndUpdateLayout('It looks like you missed some information, please make the corrections below.', 'error');
            }
        }

        // display the form
        $this->view->form = $form;
    }

    public function viewAction()
    {
        // get our area
        $id = $this->getRequest()->getParam('id');
        $dto = $this->locationFacade->getAreaById($id);

        $this->view->layout()->pageHeader = $this->view->partial(
            'partials/page-header.phtml',
            array(
                'title' => 'Details: ' . $dto->getName(),
            )
        );

        // can only delete an area that is not referred to by presentations/summary/members, etc
        $this->view->can_delete = false;
        if ($this->canDeleteArea($id)) {
            $this->view->can_delete = true;
        }

        $this->view->area = $dto;
    }

    public function deleteAction()
    {
        // get our area
        $id = $this->getRequest()->getParam('id');

        try {
            $dto = $this->locationFacade->getAreaById($id);

            if ($dto) {
                $results = $this->locationFacade->deleteArea($id);
                if($results === true) {
                    $this->setFlashMessageAndRedirect('The area has been removed from the system!', 'success', array(
                        'module' => 'admin',
                        'controller' => 'region',
                        'action' => 'index'
                    ));
                } else {
                    throw new ApiException("An error occurred while deleting area.", 1);
                }
            }
        } catch (ApiException $e) {
            $this->setFlashMessageAndRedirect('An error occurred while deleting area: ' . $e->getMessage() , 'error', array(
                        'module' => 'admin',
                        'controller' => 'member',
                        'action' => 'index'));
        }
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

    protected function saveArea($data)
    {
        if (!empty($data['region'])) {
            $region = $this->locationFacade->getRegion($data['region']);
        } else if (!empty($data['region_new'])) {
            $region = new RegionDto(null, $data['region_new']);
        } else {
            throw new \ApiException('Location needs a region value.');
        }

        $area = $this->locationFacade->saveArea($data['name'], $data['city'], $data['state'], $region);
        return $area;
    }

    protected function updateArea($id, $data)
    {
        if (!empty($data['region'])) {
            $region = $this->locationFacade->getRegion($data['region']);
        } else if (!empty($data['region_new'])) {
            $region = new RegionDto(null, $data['region_new']);
        } else {
            throw new \ApiException('Location needs a region value.');
        }

        $area = $this->locationFacade->updateArea($id, $data['name'], $data['city'], $data['state'], $region);
        return $area;
    }

    protected function canDeleteArea($id)
    {
        $core = Core::getDefaultInstance();

        // can't delete if its used in a school
        $schoolFacade = $core->load('SchoolFacade');
        $schoolDtos = $schoolFacade->getSchoolsMatching(array('area' => $id));
        if (0 < count($schoolDtos)) {
            return false;
        }

        // can't delete if a member uses it
        $memberFacade = $core->load('MemberFacade');
        $memberDtos = $memberFacade->getMembersMatching(array('area_any' => $id));
        if (0 < count($memberDtos)) {
            return false;
        }

        // not used, safe to delete
        return true;
    }
}
