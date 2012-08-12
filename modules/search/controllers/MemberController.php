<?php
use STS\Core;
use STS\Web\Controller\SecureBaseController;

class Search_MemberController extends SecureBaseController
{
    public function indexAction()
    {
        $term = $this->_request->getParam('term');
        $core = Core::getDefaultInstance();
        $facade = $core->load('MemberFacade');
        $dtos = $facade->searchForMembersByName($term);
        $data = array();
        foreach ($dtos as $dto) {
            $option = new stdClass();
            $option->id = $dto->getId();
            $option->label = $dto->getFirstName() . ' ' . $dto->getLastName();
            $data[] = $option;
        }
        $this->_helper->json($data);
    }
}
