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


    /**
     * @var \Sts\Core\Api\MemberFacade
     */
    protected $memberFacade;

    /**
     * @var \Sts\Core\Api\SchoolFacade
     */
    protected $schoolFacade;

    public function init()
    {
        parent::init();

        $core = Core::getDefaultInstance();
        $this->presentationFacade = $core->load('PresentationFacade');
        $this->locationFacade = $core->load('LocationFacade');
        $this->memberFacade = $core->load('MemberFacade');
        $this->schoolFacade = $core->load('SchoolFacade');
    }

    public function indexAction()
    {
        $this->_redirect('/admin/report/presentation');
    }

    /**
     * presentationAction
     */
    public function presentationAction()
    {
        $this->view->layout()->pageHeader = $this->view->partial(
            'partials/page-header.phtml',
            array(
                'title' => 'Presentation Summary Report'
            )
        );

        $params = $this->getRequest()->getParams();
        $form = $this->getForm();
        if ($form->isValid($params) && array_key_exists('submit', $params)) {
            $criteria = array(
                'startDate'   => $params['startDate'],
                'endDate'     => $params['endDate'],
                'regions'     => isset($params['region']) ? $params['region'] : null,
                'states'      => isset($params['state']) ? $params['state'] : null,
                'members'     => isset($params['member']) ? $params['member'] : null,
                'schoolTypes' => isset($params['school_type']) ? $params['school_type'] : null,
            );

            $csv_form = $this->getCSVForm();
            $csv_form->populate($params);

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
        $this->view->csv_form = $csv_form;
    }

    public function downloadAction()
    {
        $params = $this->getRequest()->getParams();
        $form = $this->getCSVForm();
        
        if ($form->isValid($params) && array_key_exists('submit', $params)) {
            $criteria = array(
                'startDate'   => $params['startDate'],
                'endDate'     => $params['endDate'],
                'regions'     => isset($params['region']) ? $params['region'] : null,
                'states'      => isset($params['state']) ? $params['state'] : null,
                'members'     => isset($params['member']) ? $params['member'] : null,
                'schoolTypes' => isset($params['school_type']) ? $params['school_type'] : null,
            );

            $presentations = $this->presentationFacade->getPresentationsMatching($criteria);
            
            echo count($presentations);
            die('oam 105');
        }
    }
    /**
     * getForm
     *
     * @return Admin_ReportBasicForm
     */
    private function getForm()
    {
        $form = new \Admin_ReportBasicForm(array(
            'regions' => $this->getRegionsArray(),
            'states'  => $this->locationFacade->getStates(),
            'members' => $this->getMembersArray(),
            'schoolTypes' => $this->schoolFacade->getSchoolTypes(),
        ));
        return $form;
    }

    /**
     * @return Admin_ReportBasicForm
     */
    private function getCSVForm()
    {
        $form = new \Admin_ReportCSVForm();
        return $form;
    }

    private function getRegionsArray()
    {
        $regionsArray = array();
        foreach ($this->locationFacade->getAllRegions() as $region) {
            $regionsArray[$region->getName()] = $region->getName();
        }
        return $regionsArray;
    }

    private function getMembersArray()
    {
        $membersArray = array();
        foreach ($this->memberFacade->getAllMembers() as $key => $member) {
            $membersArray[$member->getId()] = $member->getDisplayName();
        }

        return $membersArray;
    }
}
