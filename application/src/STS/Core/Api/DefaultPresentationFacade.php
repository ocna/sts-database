<?php
namespace STS\Core\Api;
use STS\Domain\Survey\Template;
use STS\Domain\Member;
use STS\Domain\Survey;
use STS\Domain\School;
use STS\Domain\Presentation;
use STS\Core\Api\PresentationFacade;
use STS\Core\Presentation\MongoPresentationRepository;

class DefaultPresentationFacade implements PresentationFacade
{

    private $presentationRepository;
    public function __construct($presentationRepository)
    {
        $this->presentationRepository = $presentationRepository;
    }
    public function savePresentation($enteredByUserId, $schoolId, $typeCode, $date, $notes, $memberIds, $participants,
                    $forms, $surveyId)
    {
        $school = new School();
        $school->setId($schoolId);
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
            ->setNumberOfParticipants($participants)->setNumberOfFormsReturned($forms)->setSurvey($survey)
            ->setMembers($members);
        return $this->presentationRepository->save($presentation);
    }
    public function getPresentationTypes()
    {
        return Presentation::getAvailableTypes();
    }

    public function getPresentationsForUserId($userId){
        
    }
    public static function getDefaultInstance($config)
    {
        $mongoConfig = $config->modules->default->db->mongodb;
        $auth = $mongoConfig->username ? $mongoConfig->username . ':' . $mongoConfig->password . '@' : '';
        $mongo = new \Mongo(
                        'mongodb://' . $auth . $mongoConfig->host . ':' . $mongoConfig->port . '/'
                                        . $mongoConfig->dbname);
        $mongoDb = $mongo->selectDB($mongoConfig->dbname);
        $presentationRepository = new MongoPresentationRepository($mongoDb);
        return new DefaultPresentationFacade($presentationRepository);
    }
}
