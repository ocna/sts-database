<?php

namespace STS\Core\Presentation;

class PresentationDto
{
    private $id;
    private $locationId;
    private $locationName;
    private $locationAreaCity;
    private $locationClass;
    private $numberOfParticipants;
    private $numberOfFormsReturnedPost;
    private $numberOfFormsReturnedPre;
    private $date;
    private $type;
    private $surveyId;
    private $membersArray;
    private $notes;
    private $correctBeforePercentage;
    private $correctAfterPercentage;
    private $effectivenessPercentage;

    /**
     * @param string $id
     * @param string $locationName
     * @param string $locationAreaCity
     * @param $locationClass
     * @param int $numberOfParticipants
     * @param string $date
     * @param string $type
     * @param int $postForms
     * @param int $preForms
     * @param string $schoolId
     * @param string $surveyId
     * @param array $membersArray
     * @param string $notes
     * @param float $correctBeforePercentage
     * @param float $correctAfterPercentage
     * @param float $effectivenessPercentage
     */
    public function __construct(
        $id,
        $locationName,
        $locationAreaCity,
        $locationClass,
        $numberOfParticipants,
        $date,
        $type,
        $postForms,
        $preForms,
        $schoolId,
        $surveyId,
        $membersArray,
        $notes,
        $correctBeforePercentage,
        $correctAfterPercentage,
        $effectivenessPercentage
    ) {
        $this->id = $id;
        $this->locationName = $locationName;
        $this->locationAreaCity = $locationAreaCity;
        $this->locationClass = $locationClass;
        $this->numberOfParticipants = $numberOfParticipants;
        $this->type = $type;
        $this->date = $date;
        $this->numberOfFormsReturnedPost = $postForms;
        $this->numberOfFormsReturnedPre = $preForms;
        $this->locationId = $schoolId;
        $this->surveyId = $surveyId;
        $this->membersArray = $membersArray;
        $this->notes = $notes;
        $this->correctBeforePercentage = $correctBeforePercentage;
        $this->correctAfterPercentage = $correctAfterPercentage;
        $this->effectivenessPercentage = $effectivenessPercentage;
        $this->locationClass = $locationClass;
    }
    public function getNotes()
    {
        return $this->notes;
    }
    public function getSurveyId()
    {
        return $this->surveyId;
    }
    public function getMembersArray()
    {
        return $this->membersArray;
    }
    public function getLocationId()
    {
        return $this->locationId;
    }
    public function getId()
    {
        return $this->id;
    }
    public function getType()
    {
        return $this->type;
    }
    public function getDate()
    {
        return $this->date ? date('m/d/Y', strtotime($this->date)) : null;
    }
    public function getNumberOfParticipants()
    {
        return $this->numberOfParticipants;
    }
    public function getLocationAreaCity()
    {
        return $this->locationAreaCity;
    }
    public function getLocationName()
    {
        return $this->locationName;
    }

    /**
     * @return string
     */
    public function getLocationClass()
    {
        return $this->locationClass;
    }
    public function getNumberOfFormsReturnedPost()
    {
        return $this->numberOfFormsReturnedPost;
    }
    public function getNumberOfFormsReturnedPre()
    {
        return $this->numberOfFormsReturnedPre;
    }
    public function getPreFormsPercentage()
    {
        return round(($this->numberOfFormsReturnedPre/$this->numberOfParticipants)*100);
    }
    public function getPostFormsPercentage()
    {
        return round(($this->numberOfFormsReturnedPost/$this->numberOfParticipants)*100);
    }
    public function getCorrectBeforePercentage()
    {
        return $this->correctBeforePercentage;
    }
    public function getCorrectAfterPercentage()
    {
        return $this->correctAfterPercentage;
    }
    public function getEffectivenessPercentage()
    {
        return $this->effectivenessPercentage;
    }
}
