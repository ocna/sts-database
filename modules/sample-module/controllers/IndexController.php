<?php
class SampleModule_IndexController extends \Zend_Controller_Action
{

    public function indexAction()
    {
        $sampleClass = new \SampleModule_ExampleClass();
        $message = $sampleClass->returnValueProvided('Zend Framework training course in www.zend.vn<br>Back-End');
        $this->view->show = $message;
    }
}