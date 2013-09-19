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
        $form->populate($params);

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
            $presentations = $this->prepareReportCSV($presentations, $params['vars']);

            // send output
            $this->outputCSV('presentations-'. date('Ymd') . '.csv', $presentations);
        } else {
            $go = '/admin/report/presentation?' . http_build_query($params);
            $this->setFlashMessageAndRedirect('Could not generate report output', 'error', $go);
        }
    }

    protected function prepareReportCSV($presentations, $vars)
    {
        // Prepare headers
        $header = array(
            'date',
            '# participants',
            '# forms pre',
            '# forms post',
        );

        if (in_array('schoolName', $vars)) {
            $header[] = 'school';
        }

        if (in_array('schoolType', $vars)) {
            $header[] = 'school type';
        }

        if (in_array('schoolNotes', $vars)) {
            $header[] = 'school notes';
        }

        if (in_array('schoolAddress', $vars)) {
            $header[] = 'address';
            $header[] = 'address2';
            $header[] = 'city';
            $header[] = 'state';
            $header[] = 'zip';
        }

        if (in_array('region', $vars)) {
            $header[] = 'region';
        }

        if (in_array('state', $vars)) {
            $header[] = 'state';
            $header[] = 'city';
            $header[] = 'area name';
        }

        if (in_array('member', $vars)) {
            $header[] = 'members';
        }

        if (in_array('presentationNotes', $vars)) {
            $header[] = 'presentation notes';
        }

        $csv = array();
        $csv[] = $header;

        // prepare data rows
        foreach ($presentations as $presentation) {
            /**
             * @var $presentation Sts\Domain\Presentation
             */
            $date = new DateTime($presentation->getDate());
            $row = array(
                $date->format('m/d/Y'),
                $presentation->getNumberOfParticipants(),
                $presentation->getNumberOfFormsReturnedPre(),
                $presentation->getNumberOfFormsReturnedPost(),
            );

            $school = $presentation->getLocation();

            if (in_array('schoolName', $vars)) {
                $row[] = $school->getName();
            }

            if (in_array('schoolType', $vars)) {
                $row[] = $school->getType();
            }

            if (in_array('schoolNotes', $vars)) {
                $row[] = $school->getType();
                $row[] = $school->getNotes();
            }

            if (in_array('schoolAddress', $vars)) {
                $row[] = $school->getAddress()->getLineOne();
                $row[] = $school->getAddress()->getLineTwo();
                $row[] = $school->getAddress()->getCity();
                $row[] = $school->getAddress()->getState();
                $row[] = $school->getAddress()->getZip();
            }

            if (in_array('region', $vars)) {
                $row[] = $school->getArea()->getRegion()->getName();
            }
            
            if (in_array('state', $vars)) {
                $row[] = $school->getArea()->getState();
                $row[] = $school->getArea()->getCity();
                $row[] = $school->getArea()->getName();
            }
            
            if (in_array('member', $vars)) {
                $members = array_map(
                    function($member) {
                        return $member->getFullName();
                    },
                    $presentation->getMembers()
                );

                $row[] = join('; ', $members);
            }

            // last column is notes
            if (in_array('presentationNotes', $vars)) {
                $row[] = $presentation->getNotes();
            }

            // add to overall file
            $csv[] = $row;
        }
        return $csv;
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
