<?php
use STS\Core;
use STS\Web\Controller\SecureBaseController;

class Admin_MemberController extends SecureBaseController
{

    protected $memberFacade;
    public function init()
    {
        parent::init();
        $core = Core::getDefaultInstance();
        $this->memberFacade = $core->load('MemberFacade');
    }
    public function indexAction()
    {
        $this->view->layout()->pageHeader = $this->view
            ->partial('partials/page-header.phtml', array(
                'title' => 'Members'
            ));
    }
    public function viewAction()
    {}
    public function newAction()
    {}
}
