<?php
use STS\TestUtilities\SurveyTestCase;
use STS\Core;
use STS\Core\Api\DefaultSurveyFacade;

class DefaultSurveyFacadeTest extends SurveyTestCase
{
    /**
     * @test
     */
    public function getDefaultInstance()
    {
        $this->assertInstanceOf('STS\Core\Api\DefaultSurveyFacade', $this->loadFacadeInstance());
    }
    /**
     * @test
     */
    public function loadSurveyTemplate()
    {
        $facade = $this->loadFacadeInstance();
        $template = $facade->getSurveyTemplate(1);
        $this->assertInstanceOf('STS\Domain\Survey\Template', $template);
    }
    /**
     * @test
     */
    public function saveSurvey()
    {
        $userId = 'muser';
        $templateId = 1;
        $surveyData = $this->getTestSurveyData();
        $facade = $this->loadFacadeInstance();
        $surveyId = $facade->saveSurvey($userId, $templateId, $surveyData);
        $this->assertNotNull($surveyId);
    }
    private function loadFacadeInstance()
    {
        $core = Core::getDefaultInstance();
        $facade = $core->load('SurveyFacade');
        return $facade;
    }
    private function getTestSurveyData()
    {
        return array(
                'q_1_c_1_pre' => '8', 'q_1_c_1_post' => '8', 'q_1_c_2_pre' => '9', 'q_1_c_2_post' => '9',
                'q_1_c_3_pre' => '0', 'q_1_c_3_post' => '7', 'q_2_c_T_pre' => '9', 'q_2_c_T_post' => '5',
                'q_2_c_F_pre' => '7', 'q_2_c_F_post' => '7', 'q_3_c_4_pre' => '7', 'q_3_c_4_post' => '8',
                'q_3_c_5_pre' => '8', 'q_3_c_5_post' => '6', 'q_3_c_6_pre' => '8', 'q_3_c_6_post' => '6',
                'q_3_c_7_pre' => '8', 'q_3_c_7_post' => '67', 'q_3_c_8_pre' => '8', 'q_3_c_8_post' => '5',
                'q_4_c_4_pre' => '5', 'q_4_c_4_post' => '7', 'q_4_c_9_pre' => '7', 'q_4_c_9_post' => '6',
                'q_4_c_10_pre' => '7', 'q_4_c_10_post' => '6', 'q_4_c_11_pre' => '8', 'q_4_c_11_post' => '6',
                'q_5_c_12_pre' => '8', 'q_5_c_12_post' => '6', 'q_5_c_13_pre' => '8', 'q_5_c_13_post' => '6',
                'q_5_c_14_pre' => '6', 'q_5_c_14_post' => '6', 'q_6_c_0_pre' => 'asdfasdf',
                'q_6_c_0_post' => 'asdfasdf', 'q_7_c_0_post' => 'asdfasdf', 'q_8_c_0_post' => 'asdfasdf',
                'q_9_c_0_post' => 'asdfasdf'
        );
    }
}
