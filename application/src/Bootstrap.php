<?php
class Bootstrap extends \Zend_Application_Bootstrap_Bootstrap
{

    public function _initRegisterDefaultNamespaces()
    {
        $autoloader = \Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace('Zend_');
        $autoloader->registerNamespace('HelloWorld_');
    }

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
        //Some fun to automatically add module models to the include path and register their namespaces with the autoloader
        //This handles ALL modules on bootstrap.
        $directoryContents = scandir($modulePath);
        $filter = new \Zend_Filter_Word_DashToCamelCase();
        $autoloader = \Zend_Loader_Autoloader::getInstance();
        $moduleIncludePath = array();
        foreach ($directoryContents as $potentialDirectory) {
            $moduleNamespace = $filter->filter($potentialDirectory);
            if (is_dir($modulePath . DIRECTORY_SEPARATOR . $potentialDirectory . DIRECTORY_SEPARATOR . "models" . DIRECTORY_SEPARATOR . $moduleNamespace)) {
                $autoloader->registerNamespace($moduleNamespace . "_");
                $moduleIncludePath[] = $modulePath . DIRECTORY_SEPARATOR . $potentialDirectory . DIRECTORY_SEPARATOR . "models";
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
    }
}