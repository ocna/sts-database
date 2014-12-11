<?php
namespace STS\Domain;

use STS\Domain\Member\Diagnosis;
use STS\Domain\Member\PhoneNumber;
use STS\Domain\Location\Address;
use STS\Domain\Location\Area;

class Member extends EntityWithTypes
{
    const TYPE_CAREGIVER = 'Caregiver';
    const TYPE_FAMILY_MEMBER = 'Family Member';
    const TYPE_SURVIVOR = 'Survivor';
    const TYPE_SYSTEM_USER = 'System User';

    const STATUS_ACTIVE = 'Active';
    const STATUS_INACTIVE = 'Inactive';
    const STATUS_DECEASED = 'Deceased';

    const ACTIVITY_PRESENTER = 'Presenter';
    const ACTIVITY_ONSITE_FACILITATOR = 'On-site Facilitator';
    const ACTIVITY_AREA_FACILITATOR = 'Area Facilitator';

    private $legacyId;
    private $firstName;
    private $lastName;
    private $email = null;
    private $presentsFor = array();
    private $facilitatesFor = array();
    private $coordinatesFor = array();
    private $activities = array();
    private $notes;
    /**
     * @var Address
     */
    private $address;
    private $associatedUserId = null;
    private $status;
    /**
     * @var bool
     */
    private $isVolunteer = false;
    private $dateTrained = null;
    /**
     * @var Diagnosis
     */
    private $diagnosis = null;
    private $phoneNumbers = array();
    private $canBeDeleted = true;

    public function clearPresentsFor()
    {
        $this->presentsFor = array();
        return $this;
    }

    public function clearFacilitatesFor()
    {
        $this->facilitatesFor = array();
        return $this;
    }

    public function clearCoordinatesFor()
    {
        $this->coordinatesFor = array();
        return $this;
    }

    public function clearPhoneNumbers()
    {
        $this->phoneNumbers = array();
        return $this;
    }

    public function clearActivities()
    {
        $this->activities = array();
        return $this;
    }

    public function addPhoneNumber(PhoneNumber $phoneNumber)
    {
        $this->phoneNumbers[] = $phoneNumber;
        return $this;
    }

    public function getPhoneNumbers()
    {
        return $this->phoneNumbers;
    }

    public function getDiagnosis()
    {
        return $this->diagnosis;
    }

    public function setDiagnosis(Diagnosis $diagnosis)
    {
        $this->diagnosis = $diagnosis;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getDateTrained()
    {
        return $this->dateTrained;
    }

    public function setDateTrained($dateTrained)
    {
        $this->dateTrained = $dateTrained;
        return $this;
    }

    private function getMongoDateTrained()
    {
        return $this->dateTrained ? new \MongoDate(strtotime($this->dateTrained)) : null;
    }

    public function toMongoArray()
    {
        // prepare facilities array for storing
        $facilitatesFor = array();
        /** @var Area $area */
        foreach ($this->facilitatesFor as $area) {
            $facilitatesFor[] = array("_id" => new \MongoId($area->getId()));
        }

        // prepare presentsFor array for storing
        $presentsFor= array();
        foreach ($this->presentsFor as $area) {
            $presentsFor[] = array("_id" => new \MongoId($area->getId()));
        }

        // prepare coordinatesFor array for storing
        $coordinatesFor = array();
        foreach ($this->coordinatesFor as $area) {
            $coordinatesFor[] = array("_id" => new \MongoId($area->getId()));
        }

        // prepare phoneNumbers array for storing
        $phoneNumbers = array();
        /** @var PhoneNumber $phoneNumber */
        foreach ($this->phoneNumbers as $phoneNumber) {
            $phoneNumbers[] = array("number"=> $phoneNumber->getNumber(), "type"=>$phoneNumber->getType());
        }

        // prepare activities
        $activities = array_values($this->getActivities());

        // build the array that will get saved in mongodb
        $array = array(
            'id'            => $this->id,
            'fname'         => utf8_encode($this->firstName),
            'lname'         => utf8_encode($this->lastName),
            'type'          => $this->type,
            'is_volunteer'  => $this->isVolunteer,
            'notes'         => utf8_encode($this->notes),
            'legacyid'      => $this->legacyId,
            'status'        => $this->status,
            'activities'    => $activities,
            'fullname'      => utf8_encode($this->getFullName()),
            'user_id'       => $this->associatedUserId,
            'address'       => array(
                'line_one' => utf8_encode($this->address->getLineOne()),
                'line_two' => utf8_encode($this->address->getLineTwo()),
                'city' => utf8_encode($this->address->getCity()),
                'state' => $this->address->getState(),
                'zip' => $this->address->getZip()
            ),
            'facilitates_for' => $facilitatesFor,
            'presents_for'    => $presentsFor,
            'coordinates_for' => $coordinatesFor,
            'email'           => utf8_encode($this->email),
            'date_trained'    => $this->getMongoDateTrained(),
            'diagnosis'       => array(
                'stage' => $this->diagnosis->getStage(),
                'date' => $this->diagnosis->getMongoDate()
            ),
            'phone_numbers' => $phoneNumbers,
            'dateCreated'  => new \MongoDate($this->getCreatedOn()),
            'dateUpdated'  => new \MongoDate($this->getUpdatedOn())
        );

        return $array;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public static function getAvailableStatuses()
    {
        // TODO cache results?
        $reflected = new \ReflectionClass(get_called_class());
        $statuses = array();
        foreach ($reflected->getConstants() as $key => $value) {
            if (substr($key, 0, 7) == 'STATUS_') {
                $statuses[$key] = $value;
            }
        }
        return $statuses;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public static function getAvailableStatus($key)
    {
        if (substr($key, 0, 7) != 'STATUS_') {
            throw new \InvalidArgumentException('Type key must begin with "STATUS_".');
        }
        if (!array_key_exists($key, static::getAvailableStatuses())) {
            throw new \InvalidArgumentException('No such status with given key.');
        }
        $reflected = new \ReflectionClass(get_called_class());
        return $reflected->getConstant($key);
    }

    public function setStatus($status)
    {
        if ($status !== null && !in_array($status, static::getAvailableStatuses(), true)) {
            throw new \InvalidArgumentException('No such status with given value.');
        }
        $this->status = $status;
        return $this;
    }

    /**
     * @return bool
     */
    public function isVolunteer()
    {
        return $this->isVolunteer;
    }

    /**
     * @param bool $is_volunteer
     *
     * @return $this
     */
    public function setVolunteer($is_volunteer)
    {
        if ($is_volunteer) {
            $this->isVolunteer = true;
        }
        return $this;
    }

    /**
     * getActivities
     *
     * @return array
     */
    public function getActivities()
    {
        return $this->activities;
    }

    /**
     * getAvailableActivities
     *
     * Return an array of allowed activities for members.
     *
     * @return array
     */
    public static function getAvailableActivities()
    {
        static $activities;

        if (!isset($activities)) {
            $reflected = new \ReflectionClass(get_called_class());
            $activities = array();

            foreach ($reflected->getConstants() as $key => $value) {
                // keep the ones that start with ACTIVITY_
                if (0 === strpos($key, 'ACTIVITY_')) {
                    $activities[$key] = $value;
                }
            }
        }
        return $activities;
    }

    /**
     * setActivity
     *
     * Sets an activity, if its a valid one.
     *
     * @param $activity
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setActivity($activity)
    {
        if ($activity !== null && !in_array($activity, static::getAvailableActivities(), true)) {
            throw new \InvalidArgumentException('No such activity with given value:' . $activity);
        }
        $this->activities[$activity] = $activity;
        return $this;
    }


    public function getAssociatedUserId()
    {
        return $this->associatedUserId;
    }

    public function setAssociatedUserId($associatedUserId)
    {
        $this->associatedUserId = $associatedUserId;
        return $this;
    }

    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param Address $address
     *
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    public function hasPassedAway()
    {
        $this->setStatus(self::STATUS_DECEASED);
        return $this;
    }

    public function isDeceased()
    {
        if ($this->getStatus()==self::STATUS_DECEASED) {
            return true;
        } else {
            return false;
        }
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
    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName()
    {
        return $this->lastName;
    }
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getLegacyId()
    {
        return $this->legacyId;
    }
    public function setLegacyId($legacyId)
    {
        $this->legacyId = $legacyId;
        return $this;
    }

    public function canPresentForArea($area)
    {
        $this->presentsFor[] = $area;
        return $this;
    }

    public function getPresentsForAreas()
    {
        return $this->presentsFor;
    }

    /**
     * @param Area $area
     *
     * @return $this
     */
    public function canFacilitateForArea($area)
    {
        $this->facilitatesFor[] = $area;
        return $this;
    }

    public function getFacilitatesForAreas()
    {
        return $this->facilitatesFor;
    }

    public function canCoordinateForArea($area)
    {
        $this->coordinatesFor[] = $area;
        return $this;
    }

    public function getCoordinatesForAreas()
    {
        return $this->coordinatesFor;
    }

    public function getAllAssociatedAreas()
    {
        $areas = array_merge($this->facilitatesFor, $this->presentsFor, $this->coordinatesFor);
        $areas = array_unique($areas, SORT_REGULAR);
        return $areas;
    }

    public function getAllAssociatedRegions()
    {
        $regions = array();
        $areas = $this->getAllAssociatedAreas();
        /** @var Area $area */
        foreach ($areas as $area) {
            $region = $area->getRegion()->getName();
            if (!in_array($region, $regions)) {
                $regions[] = $region;
            }
        }
        return $regions;
    }

    public function canBeDeleted()
    {
        $canBeDeleted = $this->canBeDeleted;
        if (!$canBeDeleted) {
            return false;
        }
        if (isset($this->associatedUserId)) {
            $canBeDeleted = false;
        }
        return $canBeDeleted;
    }

    public function setCanBeDeleted($canIt)
    {
        $this->canBeDeleted = $canIt;
        return $this;
    }

    public function getFullName()
    {
        return $this->firstName . ' '. $this->lastName;
    }
}
