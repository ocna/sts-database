<?php
use STS\Core;
use STS\Web\Controller\SecureBaseController;

class Search_MemberController extends SecureBaseController
{

    private $user;
    private $core;
    public function init()
    {
        parent::init();
        $this->core = Core::getDefaultInstance();
        $this->user = $this->getAuth()->getIdentity();
    }
    public function indexAction()
    {
        $term = $this->_request->getParam('term');
        $dtos = $this->getMemberVisableToMember($term);
        $data = array();
        foreach ($dtos as $dto) {
            $option = new stdClass();
            $option->id = $dto->getId();
            $option->label = $dto->getFirstName() . ' ' . $dto->getLastName();
            $data[] = $option;
        }
        $this->_helper->json($data);
    }
    private function getMemberVisableToMember($term)
    {
        $facade = $this->core->load('MemberFacade');
        $memberSpec = null;
        if ($this->user->getAssociatedMemberId() && $this->user->getRole() != 'admin') {
            $memberFacade = $this->core->load('MemberFacade');
            $memberSpec = $facade->getMemberByMemberAreaSpecForId($this->user->getAssociatedMemberId());
        }
        return $facade->searchForMembersByNameWithSpec($term, $memberSpec);
    }
}
