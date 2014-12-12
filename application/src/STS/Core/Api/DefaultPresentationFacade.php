<?php
namespace STS\Core\Api;

use STS\Core\ProfessionalGroup\MongoProfessionalGroupRepository;
use STS\Domain\Survey\Template;
use STS\Domain\Member;
use STS\Domain\Survey;
use STS\Domain\School;
use STS\Domain\Presentation;
use STS\Core\Presentation\MongoPresentationRepository;
use STS\Core\User\MongoUserRepository;
use STS\Core\Member\MongoMemberRepository;
use STS\Core\Presentation\PresentationDtoAssembler;
use STS\Core\School\MongoSchoolRepository;
use STS\Core\Survey\MongoSurveyRepository;

class DefaultPresentationFacade implements PresentationFacade
{
    /**
     * @var \STS\Core\Presentation\MongoPresentationRepository
     */
    private $presentationRepository;

    /**
     * @var \STS\Core\User\MongoUserRepository
     */
    private $userRepository;
    private $memberRepository;
    private $schoolRepository;
    private $surveyRepository;
    private $professionalGroupRepository;

    /**
     * @param MongoPresentationRepository $presentationRepository
     * @param MongoUserRepository $userRepository
     * @param MongoMemberRepository $memberRepository
     * @param MongoSchoolRepository $schoolRepository
     * @param MongoSurveyRepository $surveyRepository
     * @param MongoProfessionalGroupRepository $professionalGroupRepository
     */
    public function __construct(
        MongoPresentationRepository $presentationRepository,
        MongoUserRepository $userRepository,
        MongoMemberRepository $memberRepository,
        MongoSchoolRepository $schoolRepository,
        MongoSurveyRepository $surveyRepository,
        MongoProfessionalGroupRepository $professionalGroupRepository
    ) {
        $this->presentationRepository = $presentationRepository;
        $this->userRepository = $userRepository;
        $this->memberRepository = $memberRepository;
        $this->schoolRepository = $schoolRepository;
        $this->surveyRepository = $surveyRepository;
        $this->professionalGroupRepository = $professionalGroupRepository;
    }

    /**
     * savePresentation
     *
     * @param string $enteredByUserId
     * @param string $schoolId
     * @param string $professionalGroupId
     * @param string $typeCode
     * @param string $date
     * @param string $notes
     * @param array $memberIds
     * @param int $participants
     * @param int $forms
     * @param string $surveyId
     * @param $preForms
     * @return \STS\Core\Presentation\PresentationDto
     */
    public function savePresentation(
        $enteredByUserId,
        $schoolId,
        $professionalGroupId,
        $typeCode,
        $date,
        $notes,
        $memberIds,
        $participants,
        $forms,
        $surveyId,
        $preForms
    ) {
        $school = $this->schoolRepository->load($schoolId);
        $professional_group = $this->professionalGroupRepository->load($professionalGroupId);
        $members = array();
        foreach ($memberIds as $ids) {
            $member = new Member();
            $member->setId($ids);
            $members[] = $member;
        }
        $template = new Template();
        $survey = $template->createSurveyInstance();
        $survey->setId($surveyId);
        $presentation = new Presentation();
        $presentation->setEnteredByUserId($enteredByUserId)
            ->setLocation($school)
            ->setType(Presentation::getAvailableType($typeCode))
            ->setDate($date)
            ->setNotes($notes)
            ->setNumberOfParticipants($participants)
            ->setNumberOfFormsReturnedPost($forms)
            ->setSurvey($survey)
            ->setNumberOfFormsReturnedPre($preForms)
            ->setProfessionalGroup($professional_group)
            ->setMembers($members);
        $updatedPresentation = $this->presentationRepository->save($presentation);
        return PresentationDtoAssembler::toDto($updatedPresentation);
    }

    /**
     * updatePresentation
     *
     * @param $id
     * @param $schoolId
     * @param string $professionalGroupId
     * @param $typeCode
     * @param $date
     * @param $notes
     * @param $memberIds
     * @param $participants
     * @param $postForms
     * @param $preForms
     * @return \STS\Core\Presentation\PresentationDto
     */
    public function updatePresentation(
        $id,
        $schoolId,
        $professionalGroupId,
        $typeCode,
        $date,
        $notes,
        $memberIds,
        $participants,
        $postForms,
        $preForms
    ) {
        $presentation = $this->presentationRepository->load($id);
        $school = $this->schoolRepository->load($schoolId);
        $professional_group = $this->professionalGroupRepository->load($professionalGroupId);
        $members = array();
        foreach ($memberIds as $ids) {
            $member = new Member();
            $member->setId($ids);
            $members[] = $member;
        }
        $presentation->setLocation($school)
                     ->setProfessionalGroup($professional_group)
                     ->setType(Presentation::getAvailableType($typeCode))
                     ->setDate($date)
                     ->setNotes($notes)
                     ->setNumberOfParticipants($participants)
                     ->setNumberOfFormsReturnedPost($postForms)
                     ->setNumberOfFormsReturnedPre($preForms)
                     ->setMembers($members);
        $updatedPresentation = $this->presentationRepository->save($presentation);
        return PresentationDtoAssembler::toDto($updatedPresentation);
    }

    /**
     * getPresentationTypes
     *
     * @return array
     */
    public function getPresentationTypes()
    {
        return Presentation::getAvailableTypes();
    }

    /**
     * getPresentationById
     *
     * @param $id
     * @return \STS\Core\Presentation\PresentationDto
     */
    public function getPresentationById($id)
    {
        $presentation = $this->presentationRepository->load($id);

        // Ensure Survey has associated data
        if (! is_null($presentation->getSurvey())) {
            $survey = $this->surveyRepository->load($presentation->getSurvey()->getId());
            $presentation->setSurvey($survey);
        }

        return PresentationDtoAssembler::toDto($presentation);
    }

    /**
     * getPresentationsForUserId
     *
     * @param string $userId
     * @return array
     */
    public function getPresentationsForUserId($userId)
    {
        $user = $this->userRepository->load($userId);
        $member        = $this->memberRepository->load($user->getAssociatedMemberId());
        $presentations = $this->presentationRepository->find();
        $dtos          = array();
        foreach ($presentations as $presentation) {
            /** @var Presentation $presentation */
            if ($presentation->isAccessableByMemberUser($member, $user)) {
                $dtos[] = PresentationDtoAssembler::toDTO($presentation);
            }
        }

        return $dtos;
    }

    /**
     * @param string $member_id
     *
     * @return array
     */
    public function getPresentationsForMemberId($member_id)
    {
        $presentations = $this->presentationRepository->find(array('members' => $member_id));
        $dtos          = array();
        foreach ($presentations as $presentation) {
            /** @var Presentation $presentation */
            $dtos[] = PresentationDtoAssembler::toDTO($presentation);
        }

        return $dtos;
    }

    /**
     * @param array $criteria
     * @return array
     * @throws ApiException
     */
    public function getPresentationsMatching($criteria = array())
    {
        if (empty($criteria['startDate']) || empty($criteria['endDate'])) {
            throw new ApiException(
                'Start and End date for presentations reporting required.'
            );
        }

        $startDate = strtotime($criteria['startDate']);
        $endDate = strtotime($criteria['endDate']);

        $query = array(
            'date' => array(
                '$gte'=> new \MongoDate(min($startDate, $endDate)),
                '$lte'=> new \MongoDate(max($startDate, $endDate))
                )
            );
        $presentations = $this->presentationRepository->find($query);

        // filter by state
        if (isset($criteria['states']) && !empty($criteria['states'])) {
            $presentations = $this->filterByStates($presentations, $criteria['states']);
        }

        // filter by regions
        if (isset($criteria['regions']) && !empty($criteria['regions'])) {
            $presentations = $this->filterByRegions($presentations, $criteria['regions']);
        }

        // filter by school type
        if (isset($criteria['schoolTypes']) && !empty($criteria['schoolTypes'])) {
            $presentations = $this->filterBySchoolTypes($presentations, $criteria['schoolTypes']);
        }

        // filter by members
        if (isset($criteria['members']) && !empty($criteria['members'])) {
            $presentations = $this->filterByMembers($presentations, $criteria['members']);
        }

        // filter by areas
        if (isset($criteria['areas']) && !empty($criteria['areas'])) {
            $presentations = $this->filterByAreas($presentations, $criteria['areas']);
        }

        // filter by presentation type
        if (isset($criteria['presentationTypes']) && !empty($criteria['presentationTypes'])) {
            $presentations = $this->filterByPresentationTypes(
                $presentations,
                $criteria['presentationTypes']
            );
        }

        // filter by school type
        if (isset($criteria['schools']) && !empty($criteria['schools'])) {
            $presentations = $this->filterBySchools($presentations, $criteria['schools']);
        }

        return $presentations;
    }

    /**
     * getPresentationsSummary
     *
     * @param array $criteria
     * @return \stdClass
     */
    public function getPresentationsSummary($criteria = array())
    {
        $presentations = $this->getPresentationsMatching($criteria);

        $summary = new \stdClass();
        $summary->startDate = $criteria['startDate'];
        $summary->endDate = $criteria['endDate'];

        // summarize results
        $summary->totalPresentations = count($presentations);
        $summary->totalStudents = 0;
        $summary->geo = new \StdClass;
        $summary->schools = array();
        $summary->members = array();
        $summary->regions = array();
        $summary->schoolsUnique = array();
        $summary->memberUnique = array();

        foreach ($presentations as $presentation) {
            /** @var Presentation $presentation */
            // participants
            $students = $presentation->getNumberOfParticipants();
            // count number of students
            $summary->totalStudents += $students;

            // report by state
            $state = $presentation->getLocation()->getArea()->getState();
            if (!isset($summary->geo->$state)) {
                $summary->geo->$state = new \StdClass;
            }
            $summary->geo->$state->participants += $presentation->getNumberOfParticipants();
            $summary->geo->$state->presentations += 1;

            // report by region
            $region = $presentation->getLocation()->getArea()->getRegion();
            $summary->regions[$region->getName()]['participants'] += $presentation->getNumberOfParticipants();
            $summary->regions[$region->getName()]['presentations'] += 1;

            // report medical schools by type
            $type = $presentation->getLocation()->getType();
            if (!isset($summary->schools[$type])) {
                $summary->schools[$type] = array('presentations' => 0, 'participants' => 0);
            }

            $name = $presentation->getLocation()->getName();
            $summary->schoolsUnique[$name] = $name;
            $summary->schools[$type]['unique'][$name] = $name;
            $summary->schools[$type]['presentations'] += 1;
            $summary->schools[$type]['participants'] += $students;

            // report by member
            foreach ($presentation->getMembers() as $member) {
                /** @var Member $member */
                if (empty($criteria['members']) || in_array($member->getId(), $criteria['members'])) {
                    $summary->members[$member->getFullname()]['presentations'] += 1;
                    $summary->members[$member->getFullname()]['participants'] += $students;
                }
            }
        }

        // sorting function
        $compare = function ($a, $b) {
            if ($a['presentations'] > $b['presentations']) {
                return -1;
            } elseif ($a['presentations'] < $b['presentations']) {
                return 1;
            }

            if ($a['participants'] > $b['participants']) {
                return -1;
            } elseif ($a['participants'] < $b['participants']) {
                return 1;
            }

            return 0;
        };

        // sort schools by number of presentations
        uasort($summary->schools, $compare);
        uasort($summary->members, $compare);
        uasort($summary->regions, $compare);
        return $summary;
    }

    /**
     * filterByRegions
     *
     * @param $presentations
     * @param $regions
     * @return array
     */
    public function filterByRegions($presentations, $regions)
    {
        if (!is_array($regions)) {
            $regions = (array) $regions;
        }
        // remove empty values
        $regions = array_filter($regions);
        if (empty($regions)) {
            return $presentations;
        }

        // look for matches
        $presentations = array_filter(
            $presentations,
            function (Presentation $presentation) use ($regions) {
                $area = $presentation->getLocation()->getArea();
                return in_array($area->getRegion()->getName(), $regions);
            }
        );

        return $presentations;
    }


    /**
     * filterByStates
     *
     * @param $presentations
     * @param $states
     * @return array
     */
    public function filterByStates($presentations, $states)
    {
        if (!is_array($states)) {
            $states = (array) $states;
        }

        // remove empty values
        $states = array_filter($states);
        if (empty($states)) {
            return $presentations;
        }

        // look for matches
        $presentations = array_filter(
            $presentations,
            function (Presentation $presentation) use ($states) {
                $area = $presentation->getLocation()->getArea();
                return in_array($area->getState(), $states);
            }
        );

        return $presentations;
    }

    /**
     * filterByMembers
     *
     * @param $presentations
     * @param $members
     * @return array
     */
    public function filterByMembers($presentations, $members)
    {
        if (!is_array($members)) {
            $members = (array) $members;
        }

        // remove empty values
        $members = array_filter($members);
        if (empty($members)) {
            return $presentations;
        }

        // look for matches
        $presentations = array_filter(
            $presentations,
            function (Presentation $presentation) use ($members) {
                $participants = $presentation->getMembers();

                // get only the ids
                $ids = array_map(
                    function (Member $item) {
                        return $item->getId();
                    },
                    $participants
                );

                $matches = array_intersect($ids, $members);
                return count($matches);
            }
        );

        return $presentations;
    }

    /**
     * filterBySchoolTypes
     *
     * @param $presentations
     * @param $types
     * @return array
     */
    public function filterBySchoolTypes($presentations, $types)
    {
        if (!is_array($types)) {
            $types = (array) $types;
        }

        // remove empty values
        $types = array_filter($types);
        if (empty($types)) {
            return $presentations;
        }

        // switch the types to test into the labels
        $types = array_map(
            function ($key) {
                return School::getAvailableType($key);
            },
            $types
        );

        // look for matches
        $presentations = array_filter(
            $presentations,
            function (Presentation $presentation) use ($types) {
                return in_array($presentation->getLocation()->getType(), $types);
            }
        );

        return $presentations;
    }

    /**
     * @param $presentations
     * @param $types
     * @return array
     */
    public function filterByPresentationTypes($presentations, $types)
    {
        if (!is_array($types)) {
            $types = (array) $types;
        }

        // remove empty values
        $types = array_filter($types);
        if (empty($types)) {
            return $presentations;
        }

        // look for matches
        $facade = $this;
        $presentations = array_filter(
            $presentations,
            function (Presentation $presentation) use ($types, $facade) {
                $type = $facade->getTypeKey($presentation->getType());
                return in_array($type, $types);
            }
        );

        return $presentations;
    }

    /**
     * filterBySchools
     *
     * @param $presentations
     * @param $schools
     * @return array
     */
    public function filterBySchools($presentations, $schools)
    {
        if (!is_array($schools)) {
            $schools = (array) $schools;
        }

        // remove empty values
        $schools = array_filter($schools);
        if (empty($schools)) {
            return $presentations;
        }

        // look for matches
        $presentations = array_filter(
            $presentations,
            function (Presentation $presentation) use ($schools) {
                return in_array($presentation->getLocation()->getId(), $schools);
            }
        );

        return $presentations;
    }

    public function filterByAreas($presentations, $areas)
    {
        if (!is_array($areas)) {
            $areas = (array) $areas;
        }

        // remove empty values
        $areas = array_filter($areas);
        if (empty($areas)) {
            return $presentations;
        }

        // look for matches
        $presentations = array_filter(
            $presentations,
            function (Presentation $presentation) use ($areas) {
                return in_array($presentation->getLocation()->getArea()->getId(), $areas);
            }
        );

        return $presentations;
    }

    /**
     * getTypeKey
     *
     * @param $type
     * @return mixed
     */
    public function getTypeKey($type)
    {
        return array_search($type, Presentation::getAvailableTypes());
    }

    /**
     * getDefaultInstance
     *
     * @param $config
     * @return DefaultPresentationFacade
     */
    public static function getDefaultInstance($config)
    {
        $mongoConfig = $config->modules->default->db->mongodb;
        $auth = $mongoConfig->username ? $mongoConfig->username . ':' . $mongoConfig->password . '@' : '';
        $mongo = new \MongoClient(
            'mongodb://' . $auth . $mongoConfig->host . ':' . $mongoConfig->port . '/'
            . $mongoConfig->dbname
        );
        $mongoDb = $mongo->selectDB($mongoConfig->dbname);
        $presentationRepository = new MongoPresentationRepository($mongoDb);
        $userRepository = new MongoUserRepository($mongoDb);
        $memberRepository = new MongoMemberRepository($mongoDb);
        $schoolRepository = new MongoSchoolRepository($mongoDb);
        $surveyRepository = new MongoSurveyRepository($mongoDb);
        $professionalGroupRepository = new MongoProfessionalGroupRepository($mongoDb);
        return new DefaultPresentationFacade(
            $presentationRepository,
            $userRepository,
            $memberRepository,
            $schoolRepository,
            $surveyRepository,
            $professionalGroupRepository
        );
    }

    /**
     * @param $old
     * @param $new
     *
     * @return mixed|void
     */
    public function updateEnteredBy($old, $new)
    {
        $this->presentationRepository->updateEnteredBy($old, $new);
    }

    /**
     * @param $id
     * @return bool
     */
    public function deletePresentation($id)
    {
        $presentation = $this->presentationRepository->load($id);
        $survey = $presentation->getSurvey();
        $this->surveyRepository->delete($survey->getId());
        return $this->presentationRepository->delete($presentation->getId());
    }
}
