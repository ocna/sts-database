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

class DefaultPresentationFacade implements PresentationFacade
{

    private $presentationRepository;
    private $userRepository;
    private $memberRepository;
    private $schoolRepository;
    public function __construct($presentationRepository, $userRepository, $memberRepository, $schoolRepository)
    {
        $this->presentationRepository = $presentationRepository;
        $this->userRepository = $userRepository;
        $this->memberRepository = $memberRepository;
        $this->schoolRepository = $schoolRepository;
    }
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
    public function getPresentationTypes()
    {
        return Presentation::getAvailableTypes();
    }

    public function getPresentationById($id)
    {
        $presentation = $this->presentationRepository->load($id);
        return PresentationDtoAssembler::toDto($presentation);
    }
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
        return new DefaultPresentationFacade($presentationRepository, $userRepository, $memberRepository, $schoolRepository);
    }
}
