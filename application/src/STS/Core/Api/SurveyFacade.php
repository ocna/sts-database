<?php
namespace STS\Core\Api;

interface SurveyFacade
{
    /**
     * getSurveyTemplate
     *
     * @param $id
     * @return mixed
     */
    public function getSurveyTemplate($id);

    /**
     * getSurveyById
     *
     * @param $id
     * @return mixed
     */
    public function getSurveyById($id);

    /**
     * saveSurvey
     *
     * @param $userId
     * @param $templateId
     * @param $surveyData
     * @return mixed
     */
    public function saveSurvey($userId, $templateId, $surveyData);

    /**
     * @param $userId
     * @param $templateId
     * @param $surveyData
     * @param $surveyId
     *
     * @return mixed
     */
    public function updateSurvey($userId, $templateId, $surveyData, $surveyId);

    /**
     * Changes the entered_by_user_id field from an old user id to a new one.
     * Mainly used when a username is changed.
     *
     * @param $old
     * @param $new
     * @return mixed
     */
    public function updateEnteredBy($old, $new);
}
