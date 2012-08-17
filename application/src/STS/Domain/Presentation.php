<?php
namespace STS\Domain;
use STS\Domain\Entity;

class Presentation extends Entity
{

    private $types = array(
            'med' => 'MED', 'pa' => 'PA', 'np' => 'NP', 'ns' => 'NS', 'resobgyn' => 'RES OBGYN', 'resint' => 'RES INT',
            'other' => 'OTHER'
    );
    private $type;
    private $date;
    private $notes;
    private $numberOfParticipants;
    private $numberOfFormsReturned;
    private $location;
    private $members = array();
    private $survey;
    public function getType()
    {
        return $this->type;
    }
    public function setType($type)
    {
        if (!key_exists($type, $this->types)) {
            throw new \InvalidArgumentException('Supplied presentation type is not recognized.');
        }
        $this->type = $type;
        return $this;
    }
    public function getDate()
    {
        return $this->date;
    }
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }
    public function getNotes()
    {
        return $this->notes;
    }
    public function setNotes($notes)
    {
        $this->notes = $notes;
        return $this;
    }
    public function getNumberOfParticipants()
    {
        return $this->numberOfParticipants;
    }
    public function setNumberOfParticipants($numberOfParticipants)
    {
        $this->numberOfParticipants = $numberOfParticipants;
        return $this;
    }
    public function getNumberOfFormsReturned()
    {
        return $this->numberOfFormsReturned;
    }
    public function setNumberOfFormsReturned($numberOfFormsReturned)
    {
        $this->numberOfFormsReturned = $numberOfFormsReturned;
        return $this;
    }
    public function getLocation()
    {
        return $this->location;
    }
    public function setLocation($location)
    {
        $this->location = $location;
        return $this;
    }
    public function getMembers()
    {
        return $this->members;
    }
    public function setMembers($members)
    {
        $this->members = $members;
        return $this;
    }
    public function getSurvey()
    {
        return $this->survey;
    }
    public function setSurvey($survey)
    {
        $this->survey = $survey;
        return $this;
    }
    public static function getTypes()
    {
        $presentation = new Presentation();
        return $presentation->types;
    }
}
