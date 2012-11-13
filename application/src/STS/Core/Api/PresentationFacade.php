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

    /**
     *
     *
     * @param string  $userId
     */
    public function getPresentationsForUserId($userId);

    public function getPresentationById($id);
}
