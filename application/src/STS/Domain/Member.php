<?php
namespace STS\Domain;

use STS\Domain\EntityWithTypes;
use STS\Domain\Member\Diagnosis;
use STS\Domain\Member\PhoneNumber;

class Member extends EntityWithTypes
{
    const TYPE_CAREGIVER = 'Caregiver';
    const TYPE_FAMILY_MEMBER = 'Family Member';
    const TYPE_SURVIVOR = 'Survivor';
    const TYPE_SYSTEM_USER = 'System User';

    const STATUS_ACTIVE = 'Active';
    const STATUS_INACTIVE = 'Inactive';
    const STATUS_DECEASED = 'Deceased';

    private $legacyId;
    private $firstName;
    private $lastName;
    private $email = null;
    private $presentsFor = array();
    private $facilitatesFor = array();
    private $coordinatesFor = array();
    private $notes;
    private $deceased = false;
    private $address;
    private $associatedUserId = null;
    private $status;
    private $dateTrained = null;
    private $diagnosis = null;
    private $phoneNumbers = array();

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

    public function toMongoArray()
    {
        $facilitatesFor = array();
        foreach ($this->facilitatesFor as $area) {
            $facilitatesFor[] = array("_id" => new \MongoId($area->getId()));
        }
        $presentsFor= array();
        foreach ($this->presentsFor as $area) {
            $presentsFor[] = array("_id" => new \MongoId($area->getId()));
        }
        $coordinatesFor = array();
        foreach ($this->coordinatesFor as $area) {
            $coordinatesFor[] = array("_id" => new \MongoId($area->getId()));
        }
        $phoneNumbers = array();
        foreach ($this->phoneNumbers as $phoneNumber) {
            $phoneNumbers[] = array("number"=> $phoneNumber->getNumber(), "type"=>$phoneNumber->getType());
        }
        $array = array(
                'id' => $this->id, 'fname' => $this->firstName, 'lname'=>$this->lastName, 'type' => $this->type, 'notes' => $this->notes,
                'legacyid' => $this->legacyId, 'status'=>$this->status, 'fullname' => $this->getFullName(),
                'user_id' => $this->associatedUserId,
                'address' => array(
                        'line_one' => $this->address->getLineOne(), 'line_two' => $this->address->getLineTwo(),
                        'city' => $this->address->getCity(), 'state' => $this->address->getState(),
                        'zip' => $this->address->getZip()
                ),
                'facilitates_for' => $facilitatesFor,
                'presents_for'=> $presentsFor,
                'coordinates_for'=> $coordinatesFor,
                'email'=> $this->email,
                'date_trained' => new \MongoDate(strtotime($this->dateTrained)),
                'diagnosis' => array(
                    'stage' => $this->diagnosis->getStage(),
                    'date' => new \MongoDate(strtotime($this->diagnosis->getDate()))
                    ),
                'phone_numbers' => $phoneNumbers,
                'dateCreated' => new \MongoDate($this->getCreatedOn()),
                'dateUpdated' => new \MongoDate($this->getUpdatedOn())
        );
        return $array;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public static function getAvailableStatuses()
    {
        $reflected = new \ReflectionClass(get_called_class());
        $statuses = array();
        foreach ($reflected->getConstants() as $key => $value) {
            if (substr($key, 0, 7) == 'STATUS_') {
                $statuses[$key] = $value;
            }
        }
        return $statuses;
    }
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
        $areas = array();
        $this->addUniqueElements($areas, $this->presentsFor);
        $this->addUniqueElements($areas, $this->facilitatesFor);
        $this->addUniqueElements($areas, $this->coordinatesFor);
        return $areas;
    }
    private function addUniqueElements(&$array, $elements)
    {
        foreach ($elements as $element) {
            if (!in_array($element, $array)) {
                $array[] = $element;
            }
        }
    }
    private function getFullName()
    {
        return $this->firstName . ' '. $this->lastName;
    }
}
