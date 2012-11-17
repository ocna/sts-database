<?php
namespace STS\Core\Survey;

use STS\TestUtilities\MongoUtility;
use STS\TestUtilities\SurveyTestCase;
use STS\Core\Survey\MongoSurveyRepository;

class MongoSurveyRepositoryTest extends SurveyTestCase
{
    /**
     * @test
     */
    public function validLoadSurvey()
    {
        $mongoDb = MongoUtility::getDbConnection();
        $repo = new MongoSurveyRepository($mongoDb);
        $survey = $repo->load(self::ID);
        $this->assertEquals(self::ID, $survey->getId());
        $this->assertEquals(self::ENTERED_BY, $survey->getEnteredByUserID());
        $this->assertCount(9, $survey->getQuestions());
    }
}
