<?php
use STS\Core;
use STS\Web\Controller\SecureBaseController;
use \STS\Core\Presentation\PresentationDtoAssembler;
use STS\Domain\User;

class Admin_ReportController extends SecureBaseController
{
    /**
     * @var \Sts\Core\Api\DefaultPresentationFacade
     */
    protected $presentationFacade;

    /**
     * @var \Sts\Core\Api\DefaultLocationFacade
     */
    protected $locationFacade;

    /**
     * @var \Sts\Core\Api\DefaultMemberFacade
     */
    protected $memberFacade;

    /**
     * @var \Sts\Core\Api\DefaultSchoolFacade
     */
    protected $schoolFacade;

    /**
     * @var \STS\Core\Api\DefaultSurveyFacade
     */
    protected $surveyFacade;

    /**
     * @var \Zend_Session_Namespace
     */
    protected $session;

    public function init()
    {
        parent::init();

        $core = Core::getDefaultInstance();
        $this->presentationFacade = $core->load('PresentationFacade');
        $this->locationFacade = $core->load('LocationFacade');
        $this->memberFacade = $core->load('MemberFacade');
        $this->schoolFacade = $core->load('SchoolFacade');
        $this->surveyFacade = $core->load('SurveyFacade');
        $this->session = new \Zend_Session_Namespace('admin');
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

        /** @var STS\Core\User\UserDTO $user */
        $user = $this->getAuth()->getIdentity();

        $form = $this->getForm($user);
        $csv_form = $this->getCSVForm();

        if (User::ROLE_COORDINATOR == $user->getRole()) {
            if (!isset($params['region'])) {
                // limit filter options to regions they coordinate for
                $member = $this->memberFacade->getMemberById($user->getAssociatedMemberId());
                $params['region'] = $member->getCoordinatesForRegions();
            }
        }
        if ($form->isValid($params) && array_key_exists('submit', $params)) {
            $criteria = array(
                'startDate'   => $params['startDate'],
                'endDate'     => $params['endDate'],
                'regions'     => isset($params['region']) ? $params['region'] : null,
                'states'      => isset($params['state']) ? $params['state'] : null,
                'members'     => isset($params['member']) ? $params['member'] : null,
                'schoolTypes' => isset($params['school_type']) ? $params['school_type'] : null,
            );

            $csv_params['startDate'] = $params['startDate'];
            $csv_params['endDate'] = $params['endDate'];
            $csv_params['state'] = join(',' , $params['state']);
            $csv_params['region'] = join(',' , $params['region']);
            $csv_params['member'] = join(',' , $params['member']);
            $csv_params['schoolType'] = join(',' , $params['school_type']);
            $csv_form->populate($csv_params);

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

        $multi = array('region', 'state', 'member', 'schoolType');
        foreach ($multi as $key) {
            if (!empty($params[$key])) {
                $params[$key] = explode(',', $params[$key]);
            }
        }

        if (isset($params['schoolType'])) {
            $params['school_type'] = $params['schoolType'];
        }
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

    public function effectivenessAction()
    {
        $this->view->layout()->pageHeader = $this->view->partial(
            'partials/page-header.phtml',
            array(
                'title' => 'Presentation Effectiveness Report'
            )
        );

        $params = $this->getRequest()->getParams();
        $form = $this->getEffectivenessForm();
        $presentation_data = array();
        if ($form->isValid($params) && array_key_exists('submit', $params)) {
            $criteria = array(
                'startDate'         => $params['startDate'],
                'endDate'           => $params['endDate'],
                'regions'           => isset($params['region']) ? $params['region'] : null,
                'areas'             => isset($params['area']) ? $params['area'] : null,
                'presentationTypes' => isset($params['presentation_type']) ?
                    $params['presentation_type']
                    :
                    null,
                'schools'           => isset($params['school']) ? $params['school'] : null,
            );
            $presentations = $this->presentationFacade->getPresentationsMatching($criteria);

            if (empty($presentations)) {
                $this->view->noData = true;
            } else {
                $this->session->criteria = $criteria;

                /** @var STS\Domain\Presentation $presentation */
                foreach ($presentations as $presentation) {
                    $presentation->setSurvey($this->surveyFacade->getSurveyById
                        ($presentation->getSurvey()->getId()));
	                $dto = PresentationDtoAssembler::toDTO($presentation);
                    $presentation_data[] = array('dto' => $dto);
                }
            }

            $this->view->presentations = $presentation_data;
            $this->view->startDate = $params['startDate'];
            $this->view->endDate   = $params['endDate'];
        } else {
            $this->view->noData = true;
        }

        $this->view->form = $form;
    }

    public function downloadeffectivenessAction()
    {
        $criteria = $this->session->criteria;
        $presentations = $this->presentationFacade->getPresentationsMatching($criteria);
        $presentation_data = array();// Prepare headers
        $headers = array(
            'Date',
            'School',
            'Knowledge Level (pre)',
            'Knowledge Level (post)',
            'Effectiveness'
        );
        /** @var STS\Domain\Presentation $presentation */
        foreach ($presentations as $presentation) {
            $presentation->setSurvey($this->surveyFacade->getSurveyById
                ($presentation->getSurvey()->getId()));
	        $dto = PresentationDtoAssembler::toDTO($presentation);
            $presentation_data[] = array(
                $dto->getDate(),
                $dto->getLocationName(),
                number_format($dto->getCorrectBeforePercentage(), 2),
                number_format($dto->getCorrectAfterPercentage(), 2),
                number_format($dto->getEffectivenessPercentage(), 2)
            );
        }

        $this->outputCSV('effectiveness', $presentation_data, $headers);
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

        if (in_array('locationName', $vars)) {
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

            $location = $presentation->getLocation();

            if (in_array('locationName', $vars)) {
                $row[] = $location->getName();
            }

            if (in_array('schoolType', $vars)) {
                if ('STS\Domain\School' == get_class($location))
                {
                    $row[] = $location->getType();
                } else {
                    $row[] = 'Professional Group';
                }
            }

            if (in_array('schoolNotes', $vars)) {
                if ('STS\Domain\School' == get_class($location)) {
                    $row[] = $location->getNotes();
                } else {
                    $row[] = '';
                }
            }

            if (in_array('schoolAddress', $vars)) {
                if ('STS\Domain\School' == get_class($location)) {
                    $row[] = $location->getAddress()->getAddress();
                } else {
                    $row[] = '';
                }
            }

            if (in_array('region', $vars)) {
                $row[] = $location->getArea()->getRegion()->getName();
            }

            if (in_array('state', $vars)) {
                $row[] = $location->getArea()->getState();
                $row[] = $location->getArea()->getCity();
                $row[] = $location->getArea()->getName();
            }

            if (in_array('member', $vars)) {
                $members = array_map(
                    function(\STS\Domain\Member $member) {
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
     * @var STS\Core\User\UserDTO $user
     * @return Admin_ReportBasicForm
     */
    private function getForm($user)
    {
        if (User::ROLE_COORDINATOR == $user->getRole()) {
            // limit filter options to regions they coordinate for
            $member = $this->memberFacade->getMemberById($user->getAssociatedMemberId());
            $regions = $member->getCoordinatesForRegions();
            $states = $this->locationFacade->getStatesForRegions($regions);
            $members = $this->getMembersArray($states);
        } else {
            $regions = $this->getRegionsArray();
            $states = $this->locationFacade->getStates();
            $members = $this->getMembersArray();
        }
        $form = new \Admin_ReportBasicForm(array(
            'regions' => $regions,
            'states'  => $states,
            'members' => $members,
            'schoolTypes' => $this->schoolFacade->getSchoolTypes(),
        ));
        return $form;
    }

    /**
     * @return Admin_ReportCSVForm
     */
    private function getCSVForm()
    {
        $form = new \Admin_ReportCSVForm();
        return $form;
    }

    private function getEffectivenessForm()
    {
        $form = new \Admin_ReportEffectivenessForm(array(
            'regions'               => $this->getRegionsArray(),
            'areas'                 => $this->getAreasArray(),
            'presentationTypes'     => $this->presentationFacade->getPresentationTypes(),
            'schools'               => $this->getSchoolsArray(),
        ));

        return $form;
    }

    /**
     * @return array
     */
    private function getRegionsArray()
    {
        $regionsArray = array();
        foreach ($this->locationFacade->getAllRegions() as $region) {
            /** @var STS\Core\Location\RegionDto $region */
            $regionsArray[$region->getName()] = $region->getName();
        }
        return $regionsArray;
    }

    /**
     * @return array
     */
    private function getMembersArray($states = null)
    {
        $membersArray = array();
        foreach ($this->memberFacade->getAllMembers() as $member) {
            /** @var STS\Core\Member\MemberDto $member */

            if ($states != null) {
                if (!in_array($member->getAddressState(), $states)) {
                    continue;
                }
            }
            $membersArray[$member->getId()] = $member->getDisplayName();
        }

        return $membersArray;
    }

    /**
     * @return array
     */
    private function getAreasArray()
    {
        $areas_array = array();
        /** @var STS\Core\Location\AreaDto $area */
        foreach ($this->locationFacade->getAllAreas() as $area) {
            $areas_array[$area->getId()] = $area->getName();
        }

        return $areas_array;
    }

    /**
     * @return array
     */
    private function getSchoolsArray()
    {
        $schools_array = array();
        foreach ($this->schoolFacade->getAllSchools() as $school)
        {
            /** @var STS\Core\School\SchoolDto $school */
            $schools_array[$school->getId()] = $school->getName();
        }

        return $schools_array;
    }
}
