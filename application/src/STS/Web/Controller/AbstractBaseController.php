<?php
namespace STS\Web\Controller;
abstract class AbstractBaseController extends \Zend_Controller_Action
{
	protected $auth;
    public function init()
    {
        parent::init();
        $container = null;
        $this->view->navigation()->getContainer()->findOneByLabel('Home');
        $this->auth = \Zend_Auth::getInstance();
        $this->view->layout()->menu = $this->view->partial('partials/main-menu.phtml', array(
            
            'nav' => $this->view->navigation($container)
                ->menu()
                ->setPartial('partials/menu.phtml') , 
            'authenticated' => $this->auth->hasIdentity()
        ));
    }
}