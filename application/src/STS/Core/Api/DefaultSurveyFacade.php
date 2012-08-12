<?php
namespace STS\Core\Api;
use STS\Core\Survey\StaticTemplateRepository;
use STS\Core\Api\SurveyFacade;

class DefaultSurveyFacade implements SurveyFacade
{

    private $templateRepository;
    public function __construct($templateRepository)
    {
        $this->templateRepository = $templateRepository;
    }
    public function getSurveyTemplate($id)
    {
        return $this->templateRepository->load($id);
    }
    public static function getDefaultInstance($config)
    {
        $templateRepository = new StaticTemplateRepository();
        return new DefaultSurveyFacade($templateRepository);
    }
}
