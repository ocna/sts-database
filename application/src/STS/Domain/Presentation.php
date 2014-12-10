<?php
namespace STS\Domain;
use STS\Domain\School\Specification\MemberSchoolSpecification;
use STS\Domain\ProfessionalGroup;

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

    /**
     * @var School
     */
    private $location;
	/**
	 * @var ProfessionalGroup
	 */
	private $professionalGroup;
    private $members = array();

    /**
     * @var Survey
     */
    private $survey;
    private $enteredByUserId;

    public function toMongoArray()
    {
        $array = array(
                'id'                    => $this->id,
                'entered_by_user_id'    => $this->enteredByUserId,
                'type'                  => $this->type,
                'notes'                 => utf8_encode($this->notes),
                'nforms'                => $this->numberOfFormsReturnedPost,
                'nformspre'             => $this->numberOfFormsReturnedPre,
                'date'                  => $this->date,
                'nparticipants'         => $this->numberOfParticipants,
                'school_id'             => $this->location->getId(),
	            'professional_group_id' => $this->professionalGroup->getId(),
                'survey_id'             => $this->survey->getId(),
                'dateCreated'           => new \MongoDate($this->getCreatedOn()),
                'dateUpdated'           => new \MongoDate($this->getUpdatedOn())
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

    /**
     * @return \STS\Domain\School
     */
    public function getLocation()
    {
        return $this->location;
    }

    public function setLocation(School $location)
    {
        $this->location = $location;
        return $this;
    }

	/**
	 * @return ProfessionalGroup
	 */
	public function getProfessionalGroup()
	{
		return $this->professionalGroup;
	}

	/**
	 * @param ProfessionalGroup $professional_group
	 *
	 * @return $this
	 */
	public function setProfessionalGroup($professional_group)
	{
		$this->professionalGroup = $professional_group;
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

    /**
     * @return Survey
     */
    public function getSurvey()
    {
        return $this->survey;
    }

    /**
     * @param Survey $survey
     * @return $this
     */
    public function setSurvey(Survey $survey)
    {
        $this->survey = $survey;
        return $this;
    }

	/**
	 * @param $member
	 * @param User $user
	 *
	 * @return bool
	 */
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

    /**
     * @return float|string
     */
    public function getCorrectBeforePercentage() {
        if (! $this->numberOfFormsReturnedPre) {
            return 'N/A';
        }

	    $num_possible_correct = Survey::NUM_CORRECT_ANSWERS *
	                            $this->numberOfFormsReturnedPre;
	    $correct_before_percentage = ($this->survey->getNumCorrectBeforeResponses() /
	                                  $num_possible_correct) * 100;
	    return round($correct_before_percentage, 2);
    }

    /**
     * @return float
     */
    public function getCorrectAfterPercentage() {
        if (! $this->numberOfFormsReturnedPost) {
            return 'N/A';
        }

	    $num_possible_correct = Survey::NUM_CORRECT_ANSWERS *
	                            $this->numberOfFormsReturnedPost;
		$correct_after_percentage = ($this->survey->getNumCorrectAfterResponses() /
		                             $num_possible_correct) * 100;
        return round($correct_after_percentage, 2);
    }

    /**
     * @return float
     */
    public function getEffectivenessPercentage() {
        if (! $this->numberOfParticipants || ! $this->numberOfFormsReturnedPost) {
            return 'N/A';
        }

	    if (! $this->numberOfFormsReturnedPre) {
		    return 100;
	    }
	    $effectiveness = (
		                     (
			                     $this->getCorrectAfterPercentage()
			                     -
			                     $this->getCorrectBeforePercentage()
		                     )
		                     / $this->getCorrectBeforePercentage()
	                     ) * 100;
	    if (0 > $effectiveness) {
		    return 0;
	    }
        return round($effectiveness, 2);
    }
}
