<?php
use STS\Core;
use STS\Web\Controller\SecureBaseController;

class Admin_MemberController extends SecureBaseController
{

    protected $memberFacade;
    protected $userFacade;
    public function init()
    {
        parent::init();
        $core = Core::getDefaultInstance();
        $this->memberFacade = $core->load('MemberFacade');
        $this->userFacade = $core->load('UserFacade');
    }
    public function indexAction()
    {
        $this->view->members = $this->getMembersArray();
        $this->view->layout()->pageHeader = $this->view
            ->partial('partials/page-header.phtml', array(
                'title' => 'Members'
            ));
    }
    public function viewAction()
    {
        $id = $this->getRequest()->getParam('id');
        $member = $this->memberFacade->getMemberById($id);
        if ($member->getAssociatedUserId() != null) {
            $user = $this->userFacade->findUserById($member->getAssociatedUserId());
            $this->view->user = $user;
            $role = $user->getRole();
        } else {
            $role = 'member';
        }
        if ($member->isDeceased()) {
            $labelTitle = 'Deceased';
            $lableClass = 'label-inverse';
        } else {
            $labelTitle = $this->getRoleTitleForRole($role);
            $lableClass = $this->getRoleClassForRole($role);
        }
        $parameters = array(
                'title' => $member->getFirstName() . ' ' . $member->getLastName(),
                'label' => array(
                    'title' => $labelTitle, 'class' => $lableClass
                )
        );
        if ($member->isDeceased()) {
            $parameters['titleClass'] = 'muted';
        }
        $this->view->layout()->pageHeader = $this->view->partial('partials/page-header.phtml', $parameters);
        $this->view->member = $member;
    }
    public function newAction()
    {}
    private function getMembersArray()
    {
        $memberData = array();
        $members = $this->memberFacade->getAllMembers();
        foreach ($members as $member) {
            $data = array(
                    'firstName' => $member->getFirstName(), 'lastName' => $member->getLastName(),
                    'deceased' => $member->isDeceased(), 'city' => $member->getAddressCity(),
                    'state' => $member->getAddressState()
            );
            if ($member->getAssociatedUserId() != null) {
                $user = $this->userFacade->findUserById($member->getAssociatedUserId());
                $role = $user->getRole();
            } else {
                $role = 'member';
            }
            if ($member->isDeceased()) {
                $data['role'] = 'Deceased';
                $data['roleClass'] = 'label-inverse';
            } else {
                $data['role'] = $this->getRoleTitleForRole($role);
                $data['roleClass'] = $this->getRoleClassForRole($role);
            }
            $memberData[$member->getId()] = $data;
        }
        return $memberData;
    }
    private function getRoleTitleForRole($role)
    {
        switch ($role) {
            case 'admin':
                $role = "Site Administrator";
                break;
            case 'coordinator':
                $role = "Regional Coordinator";
                break;
            case 'facilitator':
                $role = "Area Facilitator";
                break;
            default:
                $role = "Member";
                break;
        }
        return $role;
    }
    private function getRoleClassForRole($role)
    {
        switch ($role) {
            case 'admin':
                $roleClass = "label-important";
                break;
            case 'coordinator':
                $roleClass = "label-warning";
                break;
            case 'facilitator':
                $roleClass = "label-info";
                break;
            default:
                $roleClass = "";
                break;
        }
        return $roleClass;
    }
}
