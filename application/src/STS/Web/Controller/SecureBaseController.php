<?php
namespace STS\Web\Controller;
use STS\Web\Controller\AbstractBaseController;
class SecureBaseController extends AbstractBaseController
{
    
    public function preDispatch(){
        parent::preDispatch();
        if($this->auth->hasIdentity()!=true){
            $this->_helper->redirector('login', 'index');
        }
    }
}