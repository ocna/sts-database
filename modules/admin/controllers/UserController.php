<?php
use STS\Web\Controller\SecureBaseController;
class Admin_UserController extends SecureBaseController
{
    protected $userMapper;

    public function init()
    {
        parent::init();
//         $this->userMapper = new Admin_Model_UserMapper();
    }

    public function indexAction()
    {
//         $this->view->objects = $this->userMapper->fetchAll();
        $this->view->layout()->pageHeader = $this->view->partial('partials/page-header.phtml', array(
            'title' => 'System Users' , 'add' => 'Add New User' , 
            'addRoute' => '/admin/user/add'
        ));
    }

    public function addAction()
    {
        $this->view->layout()->pageHeader = $this->view->partial('partials/page-header.phtml', array(
            'title' => 'Add System User'
        ));
    }

    public function editAction()
    {
        $this->view->layout()->pageHeader = $this->view->partial('partials/page-header.phtml', array(
            'title' => 'Edit System User: ' . $fullName
        ));
    }

    public function resetPasswordAction()
    {}

    public function disableAction()
    {}
}

