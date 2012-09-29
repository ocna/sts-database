<?php
use STS\Core;
use STS\Web\Controller\SecureBaseController;

class Search_AreaController extends SecureBaseController
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
        $term = $this->_request->getParam('term');
        
        $dtos = $this->locationFacade->searchAreasByName($term);
        
        
        $data = array();
        foreach ($dtos as $dto) {
            $option = new stdClass();
            $option->id = $dto->getId();
            $option->label = $dto->getName();
            $data[] = $option;
        }
        $this->_helper->json($data);
    }
    
}
