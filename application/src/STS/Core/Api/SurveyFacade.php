<?php
namespace STS\Core\Api;
interface SurveyFacade
{
    public function getSurveyTemplate($id);
    public function saveSurvey($userId, $templateId, $surveyData);
}
