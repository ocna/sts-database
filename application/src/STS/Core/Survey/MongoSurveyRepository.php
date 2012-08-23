<?php
namespace STS\Core\Survey;
use STS\Domain\Survey\SurveyRepository;
use STS\Domain\Survey;

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
        $results = $this->mongoDb->survey
            ->update(array(
                '_id' => new \MongoId($id)
            ), $array, array(
                'upsert' => 1, 'safe' => 1
            ));
        if (array_key_exists('upserted', $results)) {
            $survey->setId($results['upserted']->__toString());
        }
        return $survey;
    }
}
