<?php
namespace STS\Web\Controller;
use STS\Web\Controller\AbstractBaseController;
use STS\Web\Security\AclAware;
use STS\Web\Security\AclFactory;

class SecureBaseController extends AbstractBaseController implements AclAware
{

    private $acl;
    public function init()
    {
        parent::init();
        $acl = AclFactory::buildAcl();
        $this->setAcl($acl);
        $this->_helper->layout->assign('acl', $this->getAcl());
    }
    public function preDispatch()
    {
        parent::preDispatch();
        $this->sendToLoginIfNotAuthenticated();
        $this->sendToAccessDeniedIfNotAllowed();
    }
    public function setAcl(\Zend_Acl $acl)
    {
        $this->acl = $acl;
    }
    public function getAcl()
    {
        return $this->acl;
    }
    private function sendToLoginIfNotAuthenticated()
    {
        if ($this->getAuth()->hasIdentity() != true) {
            $this->_redirect('/session/login');
        }
    }
    private function sendToAccessDeniedIfNotAllowed()
    {
        $controller = $this->_request->controller;
        $module = $this->_request->module;
        $acl = $this->getAcl();
        $role = $this->getAuth()->getIdentity()->getRole();
        if ($acl->has($module)) {
            if (!$acl->isAllowed($role, $module)) {
                $this->_redirect('/error/index/access-denied');
            }
        }
        if ($acl->has($controller) && $acl->inherits($controller, $module, true)) {
            if (!$acl->isAllowed($role, $controller)) {
                $this->_redirect('/error/index/access-denied');
            }
        }
    }
}
