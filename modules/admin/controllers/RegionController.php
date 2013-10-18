<?php
use STS\Web\Controller\SecureBaseController;
use STS\Core;

class Admin_RegionController extends SecureBaseController
{
    /**
     * @var STS\Core\Api\DefaultLocationFacade
     */
    protected $locationFacade;

    /**
     * @var \Zend_Session_Namespace
     */
    protected $session;

    public function init()
    {
        parent::init();
        $core = Core::getDefaultInstance();
        $this->locationFacade = $core->load('LocationFacade');
        $this->session = new \Zend_Session_Namespace('admin');
    }

    public function indexAction()
    {
        $this->view->layout()->pageHeader = $this->view->partial(
            'partials/page-header.phtml',
            array(
                'title' => 'Regions',
                'add' => 'Add New Area',
                'addRoute' => '/admin/area/new'
            )
        );

        $regions = $this->locationFacade->getAllRegions();

        $dataArray = array();
        foreach ($regions as $region) {
            $areas = $this->locationFacade->getAreasForRegions(array($region->getName()));
            $dataArray[$region->getName()] = array('region' => $region->getName(), 'areas' => $areas);
        }
        $this->view->regions = $dataArray;
    }

    public function excelAction()
    {
        $regions = $this->locationFacade->getAllRegions();
        $headers = array(
            'Region',
            'City',
            'State'
        );
        $data = array();
        foreach ($regions as $region) {
            $areas = $this->locationFacade->getAreasForRegions(array($region->getName()));
            foreach ($areas as $area) {
                $data[] = array(
                    $region->getName(),
                    $area->getCity(),
                    $area->getState()
                );
            }
        }

        $this->outputCSV('regions', $data, $headers);
    }

    public function renameAction()
    {
        // get our area
        $name = $this->getRequest()->getParam('name');
        $dto = $this->locationFacade->getRegion($name);

        if (!$dto) {
            throw new Core\Api\ApiException('Could not load regions');
        }

        // get our form
        $form = $this->getRenameForm();
        $form->setAction('/admin/region/rename?name=' . $name);

        $form->populate(array(
            'name' => $dto->getName(),
        ));


        // process updates
        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getPost();

            if ($postData['name'] == $name) {
                $this->setFlashMessageAndUpdateLayout('If you wish to rename the region, please indicate a new name.', 'error');
            } elseif ($form->isValid($postData)) {
                try {
                    if ($updatedRegion = $this->renameRegion($name, $postData['name'])) {
                        $this->setFlashMessageAndRedirect(
                            "The area: \"{$updatedRegion->getName()}\" has been renamed!",
                            'success',
                            array(
                                'module' => 'admin',
                                'controller' => 'region',
                                'action' => 'index',
                            )
                        );
                    } else {
                        throw new Core\Api\ApiException("Could not rename region, please check name.");
                    }

                } catch (ApiException $e) {
                    $this->setFlashMessageAndUpdateLayout('An error occurred while saving this information: ' . $e->getMessage(), 'error');
                }
            } else {
                $this->setFlashMessageAndUpdateLayout('It looks like you missed some information, please make the corrections below.', 'error');
            }
        }
        $this->view->layout()->pageHeader = $this->view->partial(
            'partials/page-header.phtml',
            array(
                'title' => 'Rename: ' . $dto->getName(),
            )
        );

        $this->view->form = $form;
    }

    /**
     * getRenameForm
     *
     * @return Admin_RegionRename
     */
    protected function getRenameForm()
    {
        $form = new \Admin_RegionRename();
        return $form;
    }

    /**
     * @param string $old
     * @param string $new
     * @return Core\Location\RegionDto
     */
    protected function renameRegion($old, $new)
    {
        $region = $this->locationFacade->renameRegion($old, $new);
        return $region;
    }
}
