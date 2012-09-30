<?php
use STS\Core;
use STS\Web\Controller\SecureBaseController;

class Search_RegionController extends SecureBaseController
{

    private $locationFacade;
    public function init()
    {
        parent::init();
        $core = Core::getDefaultInstance();
        $this->locationFacade = $core->load('LocationFacade');
    }
    public function indexAction()
    {
        $dtos = $this->locationFacade->getAllRegions();
        $term = $this->_request->getParam('term');
        $data = array();
        foreach ($dtos as $dto) {
            if (preg_match("/$term/i", $dto->getName())) {
                $option = new stdClass();
                $option->id = $dto->getName();
                $option->label = $dto->getName();
                $data[] = $option;
            }
        }
        $this->_helper->json($data);
    }
}
