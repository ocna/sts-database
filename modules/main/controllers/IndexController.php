<?php
use STS\Web\Controller\AbstractBaseController;

class IndexController extends AbstractBaseController {
    
    
    public function preDispatch()
    {
        parent::preDispatch();
    
        $securityPulseNamespace = new \Zend_Session_Namespace('securityPulseNamespace');
        $isAuthenticated = isset($securityPulseNamespace->authenticated)?$securityPulseNamespace->authenticated:false;
    
        if($this->auth
                 ->hasIdentity()) {
            $this->_redirect('/main/home');
        } else {
            $this->_redirect('/session/login');
        }
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
