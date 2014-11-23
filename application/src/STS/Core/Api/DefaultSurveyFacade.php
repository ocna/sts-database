<?php
namespace STS\Core\Api;
use STS\Domain\Survey\Response\SingleResponse;
use STS\Domain\Survey\Response\PairResponse;
use STS\Domain\Survey\Question\ShortAnswer;
use STS\Core\Survey\StaticTemplateRepository;
use STS\Core\Survey\MongoSurveyRepository;
use STS\Core\Api\SurveyFacade;

class DefaultSurveyFacade implements SurveyFacade
{

    private $templateRepository;
    private $surveyRepository;
    public function __construct($templateRepository, $surveyRepository)
    {
        $this->templateRepository = $templateRepository;
        $this->surveyRepository = $surveyRepository;
    }
    public function getSurveyTemplate($id)
    {
        return $this->templateRepository->load($id);
    }

    public function getSurveyById($id)
    {
        return $this->surveyRepository->load($id);
    }
    public function saveSurvey($userId, $templateId, $surveyData)
    {
        $surveyInstance = $this->buildSurveyInstanceFromPostData($userId, $templateId, $surveyData);
        $updatedSurvey = $this->surveyRepository->save($surveyInstance);
        return $updatedSurvey->getId();
    }

    public function updateSurvey($userId, $templateId, $surveyData, $surveyId)
    {
        $surveyInstance = $this->buildSurveyInstanceFromPostData($userId, $templateId, $surveyData);
        $surveyInstance->setId($surveyId);
        $updatedSurvey = $this->surveyRepository->save($surveyInstance);
        return $updatedSurvey->getId();
    }

    private function buildSurveyInstanceFromPostData($userId, $templateId, $surveyData)
    {
        $template = $this->templateRepository->load($templateId);
        $surveyInstance = $template->createSurveyInstance();
        $surveyInstance->setEnteredByUserId($userId);
        $hash = array();
        foreach ($surveyData as $key => $answer) {
            $k = explode('_', $key);
            $hash[$k[1]][$k[3]][$k[4]] = $answer;
        }
        foreach ($hash as $questionId => $question) {
            if ($surveyInstance->getQuestion($questionId)->getType() == ShortAnswer::QUESTION_TYPE) {
                if ($surveyInstance->getQuestion($questionId)->whenAsked() == 0) {
                    $response = new PairResponse($question[0]['pre'], $question[0]['post']);
                } else {
                    $response = new SingleResponse(array_pop(array_pop($question)));
                }
            } else {
                $response = array();
                foreach ($question as $choiceId => $choice) {
                    if ($surveyInstance->getQuestion($questionId)->whenAsked() == 0) {
                        $response[$choiceId] = new PairResponse($choice['pre'], $choice['post']);
                    } else {
                        $response[$choiceId] = new SingleResponse(array_pop($choice));
                    }
                }
            }
            $surveyInstance->answerQuestion($questionId, $response);
        }
        return $surveyInstance;
    }

    /**
     * updateEnteredBy
     *
     * @param $old
     * @param $new
     */
    public function updateEnteredBy($old, $new)
    {
        $this->surveyRepository->updateEnteredBy($old, $new);
    }

    public static function getDefaultInstance($config)
    {
        $templateRepository = new StaticTemplateRepository();
        $mongoConfig = $config->modules->default->db->mongodb;
        $auth = $mongoConfig->username ? $mongoConfig->username . ':' . $mongoConfig->password . '@' : '';
        $mongo = new \MongoClient(
                        'mongodb://' . $auth . $mongoConfig->host . ':' . $mongoConfig->port . '/'
                                        . $mongoConfig->dbname);
        $mongoDb = $mongo->selectDB($mongoConfig->dbname);
        $surveyRepository = new MongoSurveyRepository($mongoDb);
        return new DefaultSurveyFacade($templateRepository, $surveyRepository);
    }
}
