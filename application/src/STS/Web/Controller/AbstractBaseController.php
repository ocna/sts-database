<?php
namespace STS\Web\Controller;
abstract class AbstractBaseController extends \Zend_Controller_Action
{

    public function init()
    {
        parent::init();
        $container = null;
        // $this->view->navigation()->getContainer()->findOneByLabel('Dashboard');
        $this->view->layout()->menu = $this->view->partial('partials/main-menu.phtml');
        
//         array(
        
//         'nav' => $this->view->navigation($container)
//         ->menu()
//         ->setPartial('partials/menu.phtml')
//         ));
    }
}