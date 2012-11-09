<?php

class Error_IndexController extends \Zend_Controller_Action
{
    public function indexAction()
    {
        $errors = $this->_getParam('error_handler');
        switch ($errors->type) {
            case \Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case \Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case \Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
            // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $this->view->message = 'Page not found';
                break;
            default:
            // application error
                $this->getResponse()->setHttpResponseCode(500);
                $this->view->message = 'Application error';
                break;
        }
        $this->view->exception = $errors->exception;
        $this->view->request = $errors->request;
        $config = Zend_Registry::get('config');
        if (in_array($config->env, array(
            'dev' , 'stg'
        ))) {
            $this->view->devEnvironment = true;
        } else {
            $this->view->devEnvironment = false;
        }
    }
    public function accessDeniedAction()
    {
    }
}
