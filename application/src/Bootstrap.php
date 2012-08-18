<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    public function _initViewSettings()
    {
        $this->registerPluginResource('view');
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('HTML5');
        $view->setBasePath(APPLICATION_PATH . "/../design/");
        return $view;
    }
    public function _initModules()
    {
        $this->bootstrap('frontController');
        $modulePath = APPLICATION_PATH . '/../modules';
        $frontController = $this->getResource('FrontController');
        $frontController->addModuleDirectory($modulePath);
        $frontController->setDefaultModule('main');
        // Some fun to automatically add module models to the include path and
        // register their namespaces with the autoloader
        // This handles ALL modules on bootstrap.
        $directoryContents = scandir($modulePath);
        $filter = new \Zend_Filter_Word_DashToCamelCase();
        $autoloader = \Zend_Loader_Autoloader::getInstance();
        $moduleIncludePath = array();
        foreach ($directoryContents as $potentialDirectory) {
            $moduleNamespace = $filter->filter($potentialDirectory);
            $registerNamespace = false;
            if (is_dir($modulePath . DIRECTORY_SEPARATOR . $potentialDirectory . DIRECTORY_SEPARATOR . "models"
                            . DIRECTORY_SEPARATOR . $moduleNamespace)) {
                $registerNamespace = true;
                $moduleIncludePath[] = $modulePath . DIRECTORY_SEPARATOR . $potentialDirectory . DIRECTORY_SEPARATOR
                                . "models";
            }
            if (is_dir($modulePath . DIRECTORY_SEPARATOR . $potentialDirectory . DIRECTORY_SEPARATOR . "forms"
                            . DIRECTORY_SEPARATOR . $moduleNamespace)) {
                $registerNamespace = true;
                $moduleIncludePath[] = $modulePath . DIRECTORY_SEPARATOR . $potentialDirectory . DIRECTORY_SEPARATOR
                                . "forms";
            }
            if ($registerNamespace) {
                $autoloader->registerNamespace($moduleNamespace . "_");
            }
        }
        if (!empty($moduleIncludePath)) {
            set_include_path(implode(PATH_SEPARATOR, $moduleIncludePath) . PATH_SEPARATOR . get_include_path());
        }
    }
    public function _initLayoutSettings()
    {
        $this->registerPluginResource('layout');
        $this->bootstrap('layout');
        $layout = $this->getResource('layout');
        $layout->setLayoutPath(APPLICATION_PATH . "/../design/layouts/");
        $layout->appFooter = $this->view->partial('partials/app-footer.phtml');
    }
    public function _initErrorHandler()
    {
        $errorControllerPlugin = new \Zend_Controller_Plugin_ErrorHandler();
        $errorControllerPlugin->setErrorHandlerModule('error')->setErrorHandlerController('index')
            ->setErrorHandlerAction('index');
        // make sure the fron controller is bootstrapped
        $this->bootstrap('frontController');
        $front = $this->getResource('frontController');
        $front->registerPlugin($errorControllerPlugin);
        if ($this->_options['resources']['frontController']['params']['displayExceptions'] == 1) {
            $front->throwExceptions(true);
        }
    }
    protected function _initHeadBlock()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('HTML5');
        $view->headTitle('STS Online Management System');
        $view->headLink()->appendStylesheet('/css/jquery-ui-1.8.16.custom.css')
            ->appendStylesheet('/css/jquery.tagedit.css')->appendStylesheet('/css/bootstrap.min.css')
            ->appendStylesheet('/css/styles.css');
        $view->headScript()->appendFile('/js/jquery-1.8.0.min.js')
            ->appendFile('/js/jquery-ui-1.8.23.custom.min.js')
            ->appendFile('/js/jquery.tagedit.js')->appendFile('/js/jquery.autoGrowInput.js')
            ->appendFile('/js/bootstrap.min.js');
    }
    protected function _initNavigation()
    {
        $this->bootstrap('layout');
        $layout = $this->getResource('layout');
        $view = $layout->getView();
        $config = new Zend_Config_Xml(APPLICATION_PATH . '/../design/config/navigation.xml');
        $navigation = new Zend_Navigation($config);
        $view->navigation($navigation);
        $view->navigation()->menu()->setPartial('partials/menu.phtml');
        //         $this->_acl = Security\AclFactory::getDefaultInstance();
        //         $this->bootstrap('view');
        //         $view = $this->getResource('view');
        //         $config = new Zend_Config_Xml(APPLICATION_PATH . '/../design/config/navigation.xml', 'nav');
        //         $navigation = new Zend_Navigation($config->toArray());
        //         $securityOtterNamespace = new \Zend_Session_Namespace('securityOtterNamespace');
        //         $role = $securityOtterNamespace->userRole;
        //         $view->navigation($navigation)
        //         ->setAcl($this->_acl)
        //         ->setRole($role);
    }
}
