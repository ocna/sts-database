<?php

class Admin_AreaController extends Web_BaseController
{

    protected $areaMapper;
    
    public function init()
    {
        parent::init();
        $this->areaMapper = new Admin_Model_AreaMapper();
    }
    
    public function indexAction()
    {
        $this->view->objects = $this->areaMapper->fetchAll();
        $this->view->layout()->pageHeader = $this->view->partial('partials/page-header.phtml', array(
        'title' => 'Areas' ,
        'add' => 'Add New Area' ,
        'addRoute' => '/admin/area/add'
        ));
    }
    
    public function addAction()
    {
        $this->view->layout()->pageHeader = $this->view->partial('partials/page-header.phtml', array(
        'title' => 'Add Area'
        ));
    }
    
    public function editAction()
    {
        $this->view->layout()->pageHeader = $this->view->partial('partials/page-header.phtml', array(
        'title' => 'Edit Area: ' . $fullName
        ));
    }
    
    public function deleteAction()
    {
    }

}

