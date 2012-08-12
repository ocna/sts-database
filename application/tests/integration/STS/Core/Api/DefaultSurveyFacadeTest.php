<?php
use STS\Core;
use STS\Core\Api\DefaultSurveyFacade;

class DefaultSurveyFacadeTest extends \PHPUnit_Framework_TestCase
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
    private function loadFacadeInstance()
    {
        $core = Core::getDefaultInstance();
        $facade = $core->load('SurveyFacade');
        return $facade;
    }
}
