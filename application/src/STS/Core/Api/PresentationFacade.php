<?php
/**
 *
 * @category sts
 * @package core
 * @subpackage api
 */

namespace STS\Core\Api;

interface PresentationFacade
{

    /**
     *
     *
     * @param string $enteredByUserId
     * @param string $schoolId
     * @param string $typeCode
     * @param string $date
     * @param string $notes
     * @param array $memberIds
     * @param int $participants
     * @param int $forms
     * @param string $surveyId
     */
    public function savePresentation($enteredByUserId, $schoolId, $typeCode, $date, $notes, $memberIds, $participants, $forms, $surveyId, $preForms);

    public function updatePresentation($id, $schoolId, $typeCode, $date, $notes, $memberIds, $participants, $postForms, $preForms);

    /**
     * getPresentationsForUserId
     *
     * @param string  $userId
     */
    public function getPresentationsForUserId($userId);

    /**
     * @param $id
     * @return mixed
     */
    public function getPresentationById($id);

    /**
     * getPresentationsSummary
     *
     * @param array $criteria
     * @return \stdClass
     */
    public function getPresentationsSummary($criteria = array());

    /**
     * updateEnteredBy
     *
     * Changes the entered_by_user_id field from an old user id to a new one. Mainly used when a username is changed.
     *
     * @param $old
     * @param $new
     * @return mixed
     */
    public function updateEnteredBy($old, $new);

    /**
     * @param $id
     * @return boolean
     */
    public function deletePresentation($id);
}
