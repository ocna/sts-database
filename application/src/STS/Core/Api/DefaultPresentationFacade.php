<?php
namespace STS\Core\Api;

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

    private $presentationRepository;
    private $userRepository;
    private $memberRepository;
    private $schoolRepository;
    private $surveyRepository;

    /**
     * __construct
     *
     * @param $presentationRepository
     * @param $userRepository
     * @param $memberRepository
     * @param $schoolRepository
     */
    public function __construct(Presentation\PresentationRepository $presentationRepository,
                                $userRepository, $memberRepository,
                                $schoolRepository, Survey\SurveyRepository $surveyRepository)
    {
        $this->presentationRepository = $presentationRepository;
        $this->userRepository = $userRepository;
        $this->memberRepository = $memberRepository;
        $this->schoolRepository = $schoolRepository;
        $this->surveyRepository = $surveyRepository;
    }

    /**
     * savePresentation
     *
     * @param string $enteredByUserId
     * @param string $schoolId
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
    public function savePresentation($enteredByUserId, $schoolId, $typeCode, $date, $notes, $memberIds, $participants, $forms, $surveyId, $preForms)
    {
        $school = $this->schoolRepository->load($schoolId);
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
        $presentation->setEnteredByUserId($enteredByUserId)->setLocation($school)
            ->setType(Presentation::getAvailableType($typeCode))->setDate($date)->setNotes($notes)
            ->setNumberOfParticipants($participants)->setNumberOfFormsReturnedPost($forms)->setSurvey($survey)
            ->setNumberOfFormsReturnedPre($preForms)
            ->setMembers($members);
        $updatedPresentation = $this->presentationRepository->save($presentation);
        return PresentationDtoAssembler::toDto($updatedPresentation);
    }

    /**
     * updatePresentation
     *
     * @param $id
     * @param $schoolId
     * @param $typeCode
     * @param $date
     * @param $notes
     * @param $memberIds
     * @param $participants
     * @param $postForms
     * @param $preForms
     * @return \STS\Core\Presentation\PresentationDto
     */
    public function updatePresentation($id, $schoolId, $typeCode, $date, $notes, $memberIds, $participants, $postForms, $preForms)
    {
        $presentation = $this->presentationRepository->load($id);
        $school = $this->schoolRepository->load($schoolId);
        foreach ($memberIds as $ids) {
            $member = new Member();
            $member->setId($ids);
            $members[] = $member;
        }
        $presentation->setLocation($school)
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
        $member = $this->memberRepository->load($user->getAssociatedMemberId());
        $presentations = $this->presentationRepository->find();
        $dtos = array();
        foreach ($presentations as $presentation) {
            if ($presentation->isAccessableByMemberUser($member, $user)) {
                $dtos[] = PresentationDtoAssembler::toDTO($presentation);
            }
        }
        return $dtos;
    }

    /**
     * getPresentationsSummary
     *
     * @param array $criteria
     * @return \stdClass
     */
    public function getPresentationsSummary($criteria = array())
    {
        $summary = new \stdClass();
        $summary->startDate = $criteria['startDate'];
        $summary->endDate = $criteria['endDate'];
        $startDate = strtotime($summary->startDate);
        $endDate = strtotime($summary->endDate);
        $query = array(
            'date' => array(
                '$gte'=> new \MongoDate(min($startDate, $endDate)),
                '$lt'=> new \MongoDate(max($startDate, $endDate))
                )
            );
        $presentations = $this->presentationRepository->find($query);

        $summary->totalPresentations = count($presentations);
        $summary->totalStudents = 0;
        foreach ($presentations as $presentation) {
            $summary->totalStudents += $presentation->getNumberOfParticipants();
            $state = $presentation->getLocation()->getArea()->getState();
            $summary->geo->$state->participants += $presentation->getNumberOfParticipants();
            $summary->geo->$state->presentations += 1;
        }
        return $summary;
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
        $mongo = new \Mongo('mongodb://' . $auth . $mongoConfig->host . ':' . $mongoConfig->port . '/' . $mongoConfig->dbname);
        $mongoDb = $mongo->selectDB($mongoConfig->dbname);
        $presentationRepository = new MongoPresentationRepository($mongoDb);
        $userRepository = new MongoUserRepository($mongoDb);
        $memberRepository = new MongoMemberRepository($mongoDb);
        $schoolRepository = new MongoSchoolRepository($mongoDb);
        $surveyRepository = new MongoSurveyRepository($mongoDb);
        return new DefaultPresentationFacade($presentationRepository, $userRepository,
            $memberRepository, $schoolRepository, $surveyRepository);
    }

    /**
     * updateEnteredBy
     *
     * @param $old
     * @param $new
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
