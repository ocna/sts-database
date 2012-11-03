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

    public function build()
    {
        return new PresentationDto($this->id, $this->schoolName, $this->schoolAreaCity, $this->numberOfParticipants, $this->date, $this->type);
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
}
