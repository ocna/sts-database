<?php
namespace STS\Core\Survey;

use STS\Domain\Survey\SurveyRepository;
use STS\Domain\Survey;
use STS\Domain\Survey\Question\MultipleChoice;
use STS\Domain\Survey\Question\ShortAnswer;
use STS\Domain\Survey\Question\TrueFalse;
use STS\Domain\Survey\Response\PairResponse;
use STS\Domain\Survey\Response\SingleResponse;

class MongoSurveyRepository implements SurveyRepository
{

    private $mongoDb;
    public function __construct($mongoDb)
    {
        $this->mongoDb = $mongoDb;
    }
    public function save($survey)
    {
        if (!$survey instanceof Survey) {
            throw new \InvalidArgumentException('Instance of Survey expected.');
        }
        $array = $survey->toArray();
        $id = array_shift($array);
        $results = $this->mongoDb->survey->update(
            array(
                '_id' => new \MongoId($id)
            ),
            $array,
            array(
                'upsert' => 1, 'safe' => 1
            )
        );
        if (array_key_exists('upserted', $results)) {
            $survey->setId($results['upserted']->__toString());
        }
        return $survey;
    }

    public function load($id)
    {
        $surveyData = $this->mongoDb->selectCollection('survey')->findOne(
            array(
                '_id'=> new \MongoId($id)
            )
        );
        if (is_null($surveyData)) {
            throw new \InvalidArgumentException('Survey not found for given id.');
        }
        return $this->mapData($surveyData);
    }

    private function mapData($data)
    {
        $questions = array();
        foreach ($data['questions'] as $questionData) {
            $questions[] = $this->mapQuestionData($questionData);
        }
        $survey = new Survey($questions);
        $survey->setId($data['_id']->__toString())
               ->setEnteredByUserId($data['entered_by_user_id']);
        return $survey;
    }

    private function mapQuestionData($data)
    {
        $question = $this->questionFactory($data['type']);
        $question->setId($data['id'])
                 ->setPrompt($data['prompt'])
                 ->isAsked($data['asked']);
        if (array_key_exists('response', $data)) {
            $response  = $this->responseFactory($data['response']);
            $question->setResponse($response);
        }
        if (array_key_exists('choices', $data)) {
            foreach ($data['choices'] as $choice) {
                $question->addChoice($choice['id'], $choice['prompt']);
                $response = $this->responseFactory($choice['response']);
                $question->addResponse($choice['id'], $response);
            }
        }
        return $question;
    }

    private function questionFactory($type)
    {
        switch ($type) {
            case 'MultipleChoice':
                return new MultipleChoice();
                break;
            case 'ShortAnswer':
                return new ShortAnswer();
                break;
            case 'TrueFalse':
                return new TrueFalse();
                break;
        }
    }

    private function responseFactory($data)
    {
        switch ($data['type']) {
            case 'Pair':
                return new PairResponse($data['beforeValue'], $data['afterValue']);
                break;
            case 'Single':
                return new SingleResponse($data['value']);
                break;
        }
    }

    /**
     * updateEnteredBy
     *
     * @param $old
     * @param $new
     */
    public function updateEnteredBy($old, $new)
    {
        $results = $this->mongoDb->survey->update(
            array('entered_by_user_id' => $old),
            array('$set' => array('entered_by_user_id' => $new)),
            array(
                'multiple' => 1
            )
        );

        return $results;
    }
}
