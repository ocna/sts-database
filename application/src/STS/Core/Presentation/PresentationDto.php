<?php

namespace STS\Core\Presentation;

class PresentationDto
{

    private $id;
    private $schoolName;
    private $schoolAreaCity;
    private $numberOfParticipants;
    private $numberOfFormsReturnedPost;
    private $numberOfFormsReturnedPre;
    private $date;
    private $type;

    /**
      * @param string $id
      * @param string $schoolName
      * @param string $schoolAreaCity
      * @param int $numberOfParticipants
      * @param string $date
      * @param string $type
      */
    public function __construct($id, $schoolName, $schoolAreaCity, $numberOfParticipants, $date, $type, $postForms, $preForms)
    {
        $this->id = $id;
        $this->schoolName = $schoolName;
        $this->schoolAreaCity = $schoolAreaCity;
        $this->numberOfParticipants = $numberOfParticipants;
        $this->type = $type;
        $this->date = $date;
        $this->numberOfFormsReturnedPost = $postForms;
        $this->numberOfFormsReturnedPre = $preForms;
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
