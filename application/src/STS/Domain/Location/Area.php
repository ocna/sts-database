<?php
namespace STS\Domain\Location;

use STS\Domain\Entity;

class Area extends Entity
{
    protected $id;
    protected $name;
    protected $legacyId;
    protected $city;
    protected $state;

    /**
     * @var Region
     */
    protected $region;

    /**
     * @return Region
     */
    public function getRegion()
    {
        return $this->region;
    }

    public function setRegion(Region $region)
    {
        $this->region = $region;
        return $this;
    }

    public function getState()
    {
        return $this->state;
    }

    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setCity($city)
    {
        $this->city = $city;
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

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function toMongoArray()
    {
        // copy values into an array
        $array = array(
            'id' => $this->id,
            'city' => utf8_encode($this->city),
            'name' => utf8_encode($this->name),
            'region' => array(
                'legacyid' => $this->region->getLegacyId(),
                'name' => $this->region->getName(),
            ),
            'state' => $this->state,
            'dateCreated' => new \MongoDate($this->getCreatedOn()),
            'dateUpdated' => new \MongoDate($this->getUpdatedOn())
        );

        // add legacyId if we have one
        if (!empty($this->legacyId)) {
            $array['legacyid'] = $this->legacyId;
        }

        return $array;
    }
}
