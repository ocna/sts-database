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
     * @param string $enteredByUserId
     * @param $locationId
     * @param $locationClass
     * @param string $typeCode
     * @param string $date
     * @param string $notes
     * @param array $memberIds
     * @param int $participants
     * @param int $forms
     * @param string $surveyId
     * @param $preForms
     * @return
     * @internal param string $schoolId
     */
    public function savePresentation(
        $enteredByUserId,
        $locationId,
        $locationClass,
        $typeCode,
        $date,
        $notes,
        $memberIds,
        $participants,
        $forms,
        $surveyId,
        $preForms
    );

    /**
     * @param $id
     * @param $locationId
     * @param $locationClass
     * @param $typeCode
     * @param $date
     * @param $notes
     * @param $memberIds
     * @param $participants
     * @param $postForms
     * @param $preForms
     * @return mixed
     * @internal param $schoolId
     */
    public function updatePresentation(
        $id,
        $locationId,
        $locationClass,
        $typeCode,
        $date,
        $notes,
        $memberIds,
        $participants,
        $postForms,
        $preForms
    );

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
     * Changes the entered_by_user_id field from an old user id to a new one.
     * Mainly used when a username is changed.
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
