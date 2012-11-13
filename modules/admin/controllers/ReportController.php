<?php

use STS\Web\Controller\SecureBaseController;

class Admin_ReportController extends SecureBaseController {

    public function indexAction()
    {
        $this->_redirect('/admin/report/presentation');
    }

    public function presentationAction()
    {
        $this->view->layout()->pageHeader = $this->view
            ->partial('partials/page-header.phtml', array(
                'title' => 'Presentation Summary Report'
            ));
    }
}