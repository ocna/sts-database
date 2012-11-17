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
            $this->notes
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
      */
    public function withSchoolId($schoolId)
    {
        $this->schoolId = $schoolId;
        return $this;
    }

     /**
      * @param string $id
      */
    public function withSurveyId($surveyId)
    {
        $this->surveyId = $surveyId;
        return $this;
    }

     /**
      * @param string $id
      */
    public function withId($id)
    {
        $this->id = $id;
        return $this;
    }

     /**
      * @param string $schoolName
      */
    public function withSchoolName($schoolName)
    {
        $this->schoolName = $schoolName;
        return $this;
    }

     /**
      * @param string $schoolAreaCity
      */
    public function withSchoolAreaCity($schoolAreaCity)
    {
        $this->schoolAreaCity = $schoolAreaCity;
        return $this;
    }

     /**
      * @param int $numberOfParticipants
      */
    public function withNumberOfParticipants($numberOfParticipants)
    {
        $this->numberOfParticipants = $numberOfParticipants;
        return $this;
    }

     /**
      * @param string $date
      */
    public function withDate($date)
    {
        $this->date = $date;
        return $this;
    }

     /**
      * @param string $type
      */
    public function withType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function withNumberOfFormsReturnedPost($formsPost)
    {
        $this->numberOfFormsReturnedPost = $formsPost;
        return $this;
    }

    public function withNumberOfFormsReturnedPre($formsPre)
    {
        $this->numberOfFormsReturnedPre = $formsPre;
        return $this;
    }
}
