<?php
use STS\Web\Controller\AbstractBaseController;
use STS\Web\Security\DefaultAuthAdapter;

class IndexController extends AbstractBaseController {
    public function indexAction() {
        $this->_forward('login');
    }
    public function loginAction() {
        if ($this->auth
                 ->hasIdentity()) {
            $this->_helper
                    ->redirector('index', 'home', 'main');
        }
        $this->view
                ->form = new Main_Login();
        $request = $this->getRequest();
        $form = new Main_Login();
        if ($this->getRequest()
                 ->isPost()) {
            if ($form->isValid($request->getPost())) {
                $auth = Zend_Auth::getInstance();
                $defaultAuthAdapter = DefaultAuthAdapter::getDefaultInstance($request->getParam('userName'), $request->getParam('password'));
                $authResult = $auth->authenticate($defaultAuthAdapter);
                if ($authResult->getCode() == \Zend_Auth_Result::SUCCESS) {
                    return $this->_helper
                                ->redirector('index', 'home');
                } else {
                    $this->view
                            ->loginError = "Your email or password is invalid, please check them and try again.";
                }
            }
        }
        $this->view
                ->form = $form;
    }
    public function logoutAction() {
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
        $this->_forward('login');
    }
    public function forgotPasswordAction() {
        $this->view
                ->layout()
                ->pageHeader = $this->view
                                    ->partial('partials/page-header.phtml', array(
                                    'title' => 'Forgot Password'
                                    ));
        $this->view
                ->form = new Main_ForgotPassword();
    }
    public function resetPasswordAction() {
        $this->view
                ->layout()
                ->pageHeader = $this->view
                                    ->partial('partials/page-header.phtml', array(
                                    'title' => 'Reset Password'
                                    ));
        $this->view
                ->form = new Main_ResetPassword();
    }
}
