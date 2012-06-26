<?php
class Admin_RegionController extends Web_BaseController
{
    protected $regionMapper;

    public function init()
    {
        parent::init();
        $this->regionMapper = new Admin_Model_RegionMapper();
    }

    public function indexAction()
    {
        $this->view->objects = $this->regionMapper->fetchAll();
        $this->view->layout()->pageHeader = $this->view->partial('partials/page-header.phtml', array(
            'title' => 'Regions' , 
            'add' => 'Add New Region' , 
            'addRoute' => '/admin/region/add'
        ));
    }

    public function addAction()
    {
        $this->view->layout()->pageHeader = $this->view->partial('partials/page-header.phtml', array(
            'title' => 'Add Region'
        ));
    }

    public function editAction()
    {
        $this->view->layout()->pageHeader = $this->view->partial('partials/page-header.phtml', array(
            'title' => 'Edit Region: ' . $fullName
        ));
    }

    public function deleteAction()
    {}
}

