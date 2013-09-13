<?php
use STS\Core;
use STS\Web\Controller\SecureBaseController;

class Admin_ReportController extends SecureBaseController
{
    /**
     * @var \Sts\Core\Api\PresentationFacade
     */
    protected $presentationFacade;

    /**
     * @var \Sts\Core\Api\LocationFacade
     */
    protected $locationFacade;
    
    public function init()
    {
        parent::init();
        $core = Core::getDefaultInstance();

        /**
         * @var \Sts\Core\Api\DefaultPresentationFacade
         */
        $this->presentationFacade = $core->load('PresentationFacade');

        /**
         * @var \Sts\Core\Api\DefaultLocationFacade
         */
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
        
        // $form->setDefaults($params);
        if ($form->isValid($params) && array_key_exists('submit', $params)) {
            $criteria = array(
                'startDate' => $params['startDate'],
                'endDate'   => $params['endDate'],
                'regions'   => $params['region'],
                'states'    => $params['state'],
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
        $states = array_merge(array('' => '-- Any State --'), $this->locationFacade->getStates());
        $form = new \Admin_ReportBasicForm(array(
            'regions' => $this->getRegionsArray(),
            'states' => $states,
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
