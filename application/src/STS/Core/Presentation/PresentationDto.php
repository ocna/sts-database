<?php

namespace STS\Core\Presentation;

class PresentationDto
{

    private $id;
    private $schoolId;
    private $schoolName;
    private $schoolAreaCity;
	private $professionalGroupName;
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
     * @param string $schoolName
     * @param string $schoolAreaCity
     * @param string $professional_group_name
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
    public function __construct($id, $schoolName, $schoolAreaCity,
	    $professional_group_name, $numberOfParticipants, $date, $type, $postForms, $preForms, $schoolId, $surveyId, $membersArray, $notes, $correctBeforePercentage,
	    $correctAfterPercentage, $effectivenessPercentage)
    {
        $this->id = $id;
        $this->schoolName = $schoolName;
        $this->schoolAreaCity = $schoolAreaCity;
	    $this->professionalGroupName = $professional_group_name;
        $this->numberOfParticipants = $numberOfParticipants;
        $this->type = $type;
        $this->date = $date;
        $this->numberOfFormsReturnedPost = $postForms;
        $this->numberOfFormsReturnedPre = $preForms;
        $this->schoolId = $schoolId;
        $this->surveyId = $surveyId;
        $this->membersArray = $membersArray;
        $this->notes = $notes;
	    $this->correctBeforePercentage = $correctBeforePercentage;
	    $this->correctAfterPercentage = $correctAfterPercentage;
	    $this->effectivenessPercentage = $effectivenessPercentage;
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

	public function getProfessionalGroupName()
	{
		return $this->professionalGroupName;
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
