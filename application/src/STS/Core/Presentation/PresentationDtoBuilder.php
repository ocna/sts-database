<?php

namespace STS\Core\Presentation;

class PresentationDtoBuilder
{
    private $id = null;
    private $schoolName = null;
    private $schoolAreaCity = null;
    private $numberOfParticipants = null;
    private $date = null;
    private $type = null;
    private $numberOfFormsReturnedPost = null;
    private $numberOfFormsReturnedPre  = null;
    private $schoolId = null;
    private $surveyId = null;
    private $membersArray = array();
    private $notes = null;
	private $correctBeforePercentage = null;
	private $correctAfterPercentage = null;
	private $effectivenessPercentage = null;

    public function build()
    {
        return new PresentationDto(
            $this->id,
            $this->schoolName,
            $this->schoolAreaCity,
            $this->numberOfParticipants,
            $this->date,
            $this->type,
            $this->numberOfFormsReturnedPost,
            $this->numberOfFormsReturnedPre,
            $this->schoolId,
            $this->surveyId,
            $this->membersArray,
            $this->notes,
	        $this->correctBeforePercentage,
            $this->correctAfterPercentage,
            $this->effectivenessPercentage
        );
    }
     /**
      * withNotes
      *
      * @param string $notes
      * @return PresentationDtoBuilder $this
      */
    public function withNotes($notes)
    {
        $this->notes = $notes;
        return $this;
    }

     /**
      * withMembersArray
      *
      * @param array $membersArray
      * @return PresentationDtoBuilder $this
      */
    public function withMembersArray($membersArray)
    {
        $this->membersArray = $membersArray;
        return $this;
    }

     /**
      * @param string $id
      * @return PresentationDtoBuilder
      */
    public function withSchoolId($schoolId)
    {
        $this->schoolId = $schoolId;
        return $this;
    }

     /**
      * @param string $id
      * @return PresentationDtoBuilder
      */
    public function withSurveyId($surveyId)
    {
        $this->surveyId = $surveyId;
        return $this;
    }

     /**
      * @param string $id
      * @return PresentationDtoBuilder
      */
    public function withId($id)
    {
        $this->id = $id;
        return $this;
    }

     /**
      * @param string $schoolName
      * @return PresentationDtoBuilder
      */
    public function withSchoolName($schoolName)
    {
        $this->schoolName = $schoolName;
        return $this;
    }

     /**
      * @param string $schoolAreaCity
      * @return PresentationDtoBuilder
      */
    public function withSchoolAreaCity($schoolAreaCity)
    {
        $this->schoolAreaCity = $schoolAreaCity;
        return $this;
    }

     /**
      * @param int $numberOfParticipants
      * @return PresentationDtoBuilder
      */
    public function withNumberOfParticipants($numberOfParticipants)
    {
        $this->numberOfParticipants = $numberOfParticipants;
        return $this;
    }

     /**
      * @param string $date
      * @return PresentationDtoBuilder
      */
    public function withDate($date)
    {
        $this->date = $date;
        return $this;
    }

     /**
      * @param string $type
      * @return PresentationDtoBuilder
      */
    public function withType($type)
    {
        $this->type = $type;
        return $this;
    }

	/**
	 * @param int $formsPost
	 * @return PresentationDtoBuilder
	 */
    public function withNumberOfFormsReturnedPost($formsPost)
    {
        $this->numberOfFormsReturnedPost = $formsPost;
        return $this;
    }

	/**
	 * @param int $formsPre
	 * @return PresentationDtoBuilder
	 */
    public function withNumberOfFormsReturnedPre($formsPre)
    {
        $this->numberOfFormsReturnedPre = $formsPre;
        return $this;
    }

	/**
	 * @param float $percentage
	 * @return PresentationDtoBuilder
	 */
	public function withCorrectBeforePercentage($percentage)
	{
		$this->correctBeforePercentage = $percentage;
		return $this;
	}

	/**
	 * @param float $percentage
	 * @return PresentationDtoBuilder
	 */
	public function withCorrectAfterPercentage($percentage)
	{
		$this->correctAfterPercentage = $percentage;
		return $this;
	}

	/**
	 * @param float $percentage
	 * @return $this
	 */
	public function withEffectivenessPercentage($percentage)
	{
		$this->effectivenessPercentage = $percentage;
		return $this;
	}
}
