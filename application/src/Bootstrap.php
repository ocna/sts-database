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
            if (is_dir($modulePath . DIRECTORY_SEPARATOR . $potentialDirectory . DIRECTORY_SEPARATOR . "models" . DIRECTORY_SEPARATOR . $moduleNamespace)) {
                $registerNamespace = true;
                $moduleIncludePath[] = $modulePath . DIRECTORY_SEPARATOR . $potentialDirectory . DIRECTORY_SEPARATOR . "models";
            }
            if (is_dir($modulePath . DIRECTORY_SEPARATOR . $potentialDirectory . DIRECTORY_SEPARATOR . "forms" . DIRECTORY_SEPARATOR . $moduleNamespace)) {
                $registerNamespace = true;
                $moduleIncludePath[] = $modulePath . DIRECTORY_SEPARATOR . $potentialDirectory . DIRECTORY_SEPARATOR . "forms";
            }
            if ($registerNamespace) {
                $autoloader->registerNamespace($moduleNamespace . "_");
            }
        }
        if (! empty($moduleIncludePath)) {
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
        $errorControllerPlugin->setErrorHandlerModule('error')
            ->setErrorHandlerController('index')
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
        $view->headLink()
            ->appendStylesheet('/css/bootstrap.min.css')
            ->appendStylesheet('/css/styles.css');
        $view->headScript()
            ->appendFile('//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js')
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
        $view->navigation()
            ->menu()
            ->setPartial('partials/menu.phtml');
    }
}