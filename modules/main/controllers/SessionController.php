<?php
use STS\Core;

use STS\Web\Controller\AbstractBaseController;
use STS\Web\Security\DefaultAuthAdapter;

class SessionController extends AbstractBaseController
{
    public function loginAction()
    {
        $this->view->form = new \Main_Login();
        $request = $this->getRequest();
        $form = new \Main_Login();
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($request->getPost())) {
                $auth = Zend_Auth::getInstance();
                
                $authFacade = Core::getDefaultInstance()->load('AuthFacade');
                $defaultAuthAdapter = new DefaultAuthAdapter($request->getParam('userName'), $request
                    ->getParam('password'), $authFacade);
                
                
                $authResult = $auth->authenticate($defaultAuthAdapter);
                if ($authResult->getCode() == \Zend_Auth_Result::SUCCESS) {
                    $this->_redirect('/main/home');
                } else {
                    $this->view->loginError = "Your email or password is invalid, please check them and try again.";
                }
            }
        }
        $this->view->form = $form;
    }
    public function logoutAction()
    {
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
        
        $this->_redirect('/session/login');
    }
}
