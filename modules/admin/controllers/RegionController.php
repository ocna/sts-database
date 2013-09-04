<?php
use STS\Web\Controller\SecureBaseController;
use STS\Core;

class Admin_RegionController extends SecureBaseController
{
    protected $locationFacade;

    public function init()
    {
        parent::init();
        $core = Core::getDefaultInstance();
        $this->locationFacade = $core->load('LocationFacade');
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
}
