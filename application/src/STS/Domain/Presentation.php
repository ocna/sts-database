<?php
namespace STS\Domain;
use STS\Domain\Entity;
use STS\Domain\School\Specification\MemberSchoolSpecification;

class Presentation extends EntityWithTypes
{

    const TYPE_MED = 'MED';
    const TYPE_PA = 'PA';
    const TYPE_NP = 'NP';
    const TYPE_NS = 'NS';
    const TYPE_RES_OBGYN = 'RES OBGYN';
    const TYPE_RES_INT = 'RES INT';
    const TYPE_OTHER = 'OTHER';
    const TYPE_PENDING = "PENDING";

    private $date;
    private $notes;
    private $numberOfParticipants;
    private $numberOfFormsReturnedPre;
    private $numberOfFormsReturnedPost;
    private $location;
    private $members = array();
    private $survey;
    private $enteredByUserId;

    public function toMongoArray()
    {
        $array = array(
                'id' => $this->id, 'entered_by_user_id' => $this->enteredByUserId, 'type' => $this->type,
                'notes' => utf8_encode($this->notes), 'nforms' => $this->numberOfFormsReturnedPost,
                'nformspre'=>$this->numberOfFormsReturnedPre,
                'date' => $this->date,
                'nparticipants' => $this->numberOfParticipants, 'school_id' => $this->location->getId(),
                'survey_id' => $this->survey->getId(),
                'dateCreated' => new \MongoDate($this->getCreatedOn()),
                'dateUpdated' => new \MongoDate($this->getUpdatedOn())
        );
        $members = array();
        foreach ($this->members as $member) {
            $members[] = $member->getId();
        }
        $array['members'] = $members;
        return $array;
    }

    public function getEnteredByUserId()
    {
        return $this->enteredByUserId;
    }

    public function setEnteredByUserId($enteredByUserId)
    {
        $this->enteredByUserId = $enteredByUserId;
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

    public function getNumberOfFormsReturnedPost()
    {
        return $this->numberOfFormsReturnedPost;
    }

    public function setNumberOfFormsReturnedPost($numberOfFormsReturnedPost)
    {
        $this->numberOfFormsReturnedPost = $numberOfFormsReturnedPost;
        return $this;
    }

    public function getNumberOfFormsReturnedPre()
    {
        return $this->numberOfFormsReturnedPre;
    }

    public function setNumberOfFormsReturnedPre($numberOfFormsReturnedPre)
    {
        $this->numberOfFormsReturnedPre = $numberOfFormsReturnedPre;
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

    public function isAccessableByMemberUser($member, $user)
    {
        if ($user->getRole() == 'admin') {
            return true;
        }

        if ($user->getId() == $this->enteredByUserId) {
            return true;
        }

        $spec = new MemberSchoolSpecification($member);
        return $spec->isSatisfiedBy($this->location);
    }
}
