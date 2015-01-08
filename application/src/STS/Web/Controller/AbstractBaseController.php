<?php
namespace STS\Web\Controller;

use STS\Web\Security\AuthAware;
use Zend_Auth;

abstract class AbstractBaseController extends \Zend_Controller_Action implements AuthAware
{
    /**
     * @var Zend_Auth
     */
    private $auth;
    protected $flashMessenger = null;
    protected $flashMessengerNamespace = 'flashMessagesPulseNamespace';
    protected $flashMessengerPartialLayout = 'partials/flash-message.phtml';
    public function init()
    {
        $this->flashMessenger = $this->_helper->getHelper('FlashMessenger');
        parent::init();
        $container = null;
        $this->setAuth(\Zend_Auth::getInstance());
        $this->view->layout()->menu = $this->view->partial(
            'partials/main-menu.phtml',
            array(
                'nav'           => $this->view->navigation($container)
                    ->menu()
                    ->setPartial('partials/menu.phtml'),
                'authenticated' => $this->getAuth()->hasIdentity(),
                'userName'      => $this->getFormatedName()
            )
        );
    }

    /**
     * @param \Zend_Auth $auth
     */
    public function setAuth(\Zend_Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @return Zend_Auth
     */
    public function getAuth()
    {
        return $this->auth;
    }

    /**
     * @return string
     */
    private function getFormatedName()
    {
        if ($this->getAuth()->hasIdentity()) {
            return ucfirst($this->getAuth()->getIdentity()->getFirstName()) . ' '
               . ucfirst($this->getAuth()->getIdentity()->getLastName());
        }
    }

    /**
     * @param string $message
     * @param string $messageType
     * @return mixed
     * @throws \InvalidArgumentException
     */
    protected function buildAndSetFlashMessage($message, $messageType)
    {
        $this->_helper->flashMessenger->setNamespace($this->flashMessengerNamespace);
        switch (strtolower($messageType)) {
            case 'error':
                $title = ' Uh Oh! ';
                $flashClass = 'alert-error';
                break;
            case 'success':
                $title = ' Hooray! ';
                $flashClass = 'alert-success';
                break;
            case 'info':
                $title = ' Just a note: ';
                $flashClass = 'alert-info';
                break;
            case 'warning':
                $title = ' Warning... ';
                $flashClass = 'alert-warning';
                break;
            default:
                throw new \InvalidArgumentException('Unknown message type.');
        }
        $decoratedFlashMessage = $this->view
            ->partial(
                $this->flashMessengerPartialLayout,
                array(
                    'flashMessage' => $message, 'flashClass' => $flashClass, 'flashTitle' => $title
                )
            );
        $this->_helper->flashMessenger->addMessage($decoratedFlashMessage);
        return $decoratedFlashMessage;
    }
    /**
     * Sets a FlashMessage in the FlashMessenger's queue and redirects
     *
     * @param string $message
     * @param string $messageType:
     *            info | error | warning | success
     * @param array|string $redirectToLocation
     */
    protected function setFlashMessageAndRedirect($message, $messageType, $redirectToLocation)
    {
        $this->buildAndSetFlashMessage($message, $messageType);
        if (is_string($redirectToLocation)) {
            return $this->_redirector->gotoUrl($redirectToLocation);
        } elseif (is_array($redirectToLocation) and isset($redirectToLocation['action'])) {
            $action = $redirectToLocation['action'];
            $controller = isset($redirectToLocation['controller']) ? $redirectToLocation['controller'] : null;
            $module = isset($redirectToLocation['module']) ? $redirectToLocation['module'] : null;
            $parameters = isset($redirectToLocation['params']) ? $redirectToLocation['params'] : array();
            return $this->_helper->redirector($action, $controller, $module, $parameters);
        }
    }
    /**
     * Sets a FlashMessage in the FlashMessenger's queue and doesn't redirect
     * anywhere
     *
     * @param string $message
     * @param string $messageType:
     *            info | error | warning | success
     */
    protected function setFlashMessageAndUpdateLayout($message, $messageType)
    {
        $decoratedMessage = $this->buildAndSetFlashMessage($message, $messageType);
        $layout = $this->getHelper('layout');
        $layout->assign('flashMessage', $decoratedMessage);
    }

    /**
     * Takes array data and outputs as CSV
     *
     * @param string $file_name Base name for file (before .csv)
     * @param array $data
     * @param array $headers Optional array of column headers
     */
    protected function outputCSV($file_name, array $data, array $headers = null)
    {
        // add extension if missing
        if (!preg_match('/\.csv$/', $file_name)) {
            $file_name .= ".csv";
        }

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $this->getResponse()
            ->setHeader('Content-Description', 'File Transfer', true)
            ->setHeader('Content-Type', 'text/csv', true)
            ->setHeader('Content-Disposition', 'attachment; filename=' . $file_name, true)
            ->setHeader('Content-Transfer-Encoding', 'binary', true)
            ->setHeader('Expires', '0', true)
            ->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true)
            ->setHeader('Pragma', 'public', true);

        $output = fopen('php://output', 'w');
        ob_start();

        if (false == is_null($headers)) {
            fputcsv($output, $headers);
        }

        foreach ($data as $key => $values) {
            fputcsv($output, $values);
        }

        fclose($output);
        $csv = ob_get_clean();

        $this->getResponse()->setBody($csv);
    }
}
