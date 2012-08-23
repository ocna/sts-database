<?php
namespace STS\Core\Api;
interface PresentationFacade
{
    public function savePresentation($enteredByUserId, $schoolId, $typeCode, $date, $notes, $memberIds, $participants, $forms, $surveyId);
}
