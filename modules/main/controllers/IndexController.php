<?php
use STS\Web\Controller\AbstractBaseController;
use STS\Web\Security\DefaultAuthAdapter;
class IndexController extends AbstractBaseController
{

    function preDispatch()
    {
        parent::preDispatch();
        if ($this->auth->hasIdentity()) {
            $this->_helper->redirector('index', 'home');
        }
        $this->_helper->redirector('login');
    }

    public function loginAction()
    {
        $this->view->form = new Main_Login();
        $request = $this->getRequest();
        $form = new Main_Login();
        if ($this->getRequest()
            ->isPost()) {
            if ($form->isValid($request->getPost())) {
                $auth = Zend_Auth::getInstance();
                $defaultAuthAdapter = DefaultAuthAdapter::getDefaultInstance($request->getParam('email'), $request->getParam('password'));
                $authResult = $auth->authenticate($defaultAuthAdapter);
                if ($authResult->getCode() == \Zend_Auth_Result::SUCCESS) {
                    return $this->_helper->redirector('index', 'home');
                } else {
                    $this->view->loginError = "Your email or password is invalid, please check them and try again. If you are having trouble logging in click here to reset your password.";
                }
            }
        }
        $this->view->form = $form;
    }

    public function logoutAction()
    {
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
        return $this->_helper->redirector('index', 'login');
    }

    public function forgotPasswordAction()
    {
        $this->view->layout()->pageHeader = $this->view->partial('partials/page-header.phtml', array(
            'title' => 'Forgot Password'
        ));
        $this->view->form = new Main_ForgotPassword();
    }

    public function resetPasswordAction()
    {
        $this->view->layout()->pageHeader = $this->view->partial('partials/page-header.phtml', array(
            'title' => 'Reset Password'
        ));
        $this->view->form = new Main_ResetPassword();
    }
}