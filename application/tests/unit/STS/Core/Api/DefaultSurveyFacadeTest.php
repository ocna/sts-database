<?php
namespace STS\Core\Api;

use STS\TestUtilities\SurveyTestCase;
use STS\Core;
use STS\Core\Api\DefaultSurveyFacade;

class DefaultSurveyFacadeTest extends SurveyTestCase
{
    /**
     * @test
     */
    public function validGetSurveyById()
    {
        $templateRepo = \Mockery::mock('STS\Core\Survey\StaticTemplateRepository');
        $surveyRepo = \Mockery::mock('STS\Core\Survey\MongoSurveyRepository', array('load' => $this->getValidSurvey()));

        $facade = new DefaultSurveyFacade($templateRepo, $surveyRepo);
        $survey = $facade->getSurveyById(self::ID);
        $this->assertEquals($this->getValidSurvey(), $survey);
    }
}
