<?php

namespace STS\Core\Presentation;

class PresentationDto
{

    private $id;
    private $schoolName;
    private $schoolAreaCity;
    private $numberOfParticipants;
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
    public function __construct($id, $schoolName, $schoolAreaCity, $numberOfParticipants, $date, $type)
    {
        $this->id = $id;
        $this->schoolName = $schoolName;
        $this->schoolAreaCity = $schoolAreaCity;
        $this->numberOfParticipants = $numberOfParticipants;
        $this->type = $type;
        $this->date = $date;
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
        return $this->date ? date('n/j/Y', strtotime($this->date)) : null;
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
}
