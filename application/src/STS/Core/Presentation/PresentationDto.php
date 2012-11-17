<?php

namespace STS\Core\Presentation;

class PresentationDto
{

    private $id;
    private $schoolId;
    private $schoolName;
    private $schoolAreaCity;
    private $numberOfParticipants;
    private $numberOfFormsReturnedPost;
    private $numberOfFormsReturnedPre;
    private $date;
    private $type;
    private $surveyId;
    private $membersArray;
    private $notes;

    /**
      * @param string $id
      * @param string $schoolName
      * @param string $schoolAreaCity
      * @param int $numberOfParticipants
      * @param string $date
      * @param string $type
      */
    public function __construct($id, $schoolName, $schoolAreaCity, $numberOfParticipants, $date, $type, $postForms, $preForms, $schoolId, $surveyId, $membersArray, $notes)
    {
        $this->id = $id;
        $this->schoolName = $schoolName;
        $this->schoolAreaCity = $schoolAreaCity;
        $this->numberOfParticipants = $numberOfParticipants;
        $this->type = $type;
        $this->date = $date;
        $this->numberOfFormsReturnedPost = $postForms;
        $this->numberOfFormsReturnedPre = $preForms;
        $this->schoolId = $schoolId;
        $this->surveyId = $surveyId;
        $this->membersArray = $membersArray;
        $this->notes = $notes;
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
    public function getSchoolId()
    {
        return $this->schoolId;
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
    public function getSchoolAreaCity()
    {
        return $this->schoolAreaCity;
    }
    public function getSchoolName()
    {
        return $this->schoolName;
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
}
