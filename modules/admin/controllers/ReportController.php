<?php
use STS\Core;
use STS\Web\Controller\SecureBaseController;

class Admin_ReportController extends SecureBaseController
{
    protected $presentationFacade;
    protected $locationFacade;
    
    public function init()
    {
        parent::init();
        $core = Core::getDefaultInstance();
        $this->presentationFacade = $core->load('PresentationFacade');
        $this->locationFacade = $core->load('LocationFacade');
    }

    public function indexAction()
    {
        $this->_redirect('/admin/report/presentation');
    }

    public function presentationAction()
    {
        $this->view->layout()->pageHeader = $this->view->partial(
            'partials/page-header.phtml',
            array(
                'title' => 'Presentation Summary Report'
            )
        );
        $form = $this->getForm();
        $params = $this->getRequest()->getParams();
        
        //$form->setDefaults($params);
        if ($form->isValid($params) && array_key_exists('submit', $params)) {
            $criteria = array(
                'startDate' => $params['startDate'],
                'endDate'   => $params['endDate'],
                'regions'   => $params['region'],
            );

            $summary = $this->presentationFacade->getPresentationsSummary($criteria);
            if (0 == $summary->totalPresentations) {
                $this->view->noData = true;
            }

            $this->view->summary   = $summary;
            $this->view->startDate = $summary->startDate;
            $this->view->endDate   = $summary->endDate;
        } else {
            $this->view->noData = true;
        }

        $this->view->form = $form;
    }

    private function getForm()
    {
        $form = new \Admin_ReportBasicForm(array(
            'regions' => $this->getRegionsArray(),
        ));
        return $form;
    }

    private function getRegionsArray($label = "-- Any Region --")
    {
        $regionsArray = array('' => $label);
        foreach ($this->locationFacade->getAllRegions() as $region) {
            $regionsArray[$region->getName()] = $region->getName();
        }
        return $regionsArray;
    }
}
