<?php
//use STS\Web\Security\AclFactory;
use STS\Core;
use STS\Core\Api\ApiException;
use STS\Web\Controller\SecureBaseController;

class Presentation_CalendarController extends SecureBaseController
{
    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
        $this->view->layout()->pageHeader = $this->view->partial('partials/page-header.phtml', array(
            'title' => 'Presentation Calendar',
        ));

        // get our calendar source specified in core.xml
        $core = Core::getDefaultInstance();
        $config = $core->getConfig();

        if (!isset($config->calendar->src)) {
            throw new \RuntimeException("Calendar source must be specified in core.xml");
        }

        $this->view->cal_src = $config->calendar->src;
        $this->view->height = 800;
        $this->view->width = 960;
    }
}