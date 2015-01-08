<?php
namespace STS\Core\Presentation;

class PresentationDtoBuilder
{
    private $id = null;
    private $locationName = null;
    private $locationAreaCity = null;
    private $locationClass = null;
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
            $this->id, $this->locationName, $this->locationAreaCity, $this->locationClass,
            $this->numberOfParticipants,
            $this->date, $this->type, $this->numberOfFormsReturnedPost,
            $this->numberOfFormsReturnedPre, $this->schoolId, $this->surveyId, $this->membersArray,
            $this->notes, $this->correctBeforePercentage, $this->correctAfterPercentage,
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
      * @param string $schoolId
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
    public function withSurveyId($id)
    {
        $this->surveyId = $id;
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
      * @param string $locationName
      * @return PresentationDtoBuilder
      */
    public function withLocationName($locationName)
    {
        $this->locationName = $locationName;
        return $this;
    }

     /**
      * @param string $locationAreaCity
      * @return PresentationDtoBuilder
      */
    public function withLocationAreaCity($locationAreaCity)
    {
        $this->locationAreaCity = $locationAreaCity;
        return $this;
    }

    /**
     * @param string $locationClass
     * @return $this
     */
    public function withLocationClass($locationClass)
    {
        $this->locationClass = $locationClass;
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
