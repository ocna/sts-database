<?php
use STS\Web\Controller\AbstractBaseController;
class IndexController extends AbstractBaseController
{

    public function indexAction()
    {
        $this->_redirect("/index/login");
    }
    
    public function loginAction(){
        $this->view->form = new Main_Login();
        
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