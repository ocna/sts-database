<?php
class Admin_Model_Area
{
    protected $id;
    protected $city;
    protected $state;
    protected $regionId;
    protected $regionObject;
    protected $lastUpdatedOn;
    protected $createdOn;

    /**
     *
     * @return the $regionObject
     */
    public function getRegionObject()
    {
        return $this->regionObject;
    }

    /**
     *
     * @param $regionObject field_type           
     */
    public function setRegionObject($regionObject)
    {
        $this->regionObject = $regionObject;
    }

    /**
     *
     * @return the $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @return the $city
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     *
     * @return the $state
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     *
     * @return the $regionId
     */
    public function getRegionId()
    {
        return $this->regionId;
    }

    /**
     *
     * @return the $lastUpdatedOn
     */
    public function getLastUpdatedOn()
    {
        return $this->lastUpdatedOn;
    }

    /**
     *
     * @return the $createdOn
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     *
     * @param $id field_type           
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     *
     * @param $city field_type           
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     *
     * @param $state field_type           
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     *
     * @param $regionId field_type           
     */
    public function setRegionId($regionId)
    {
        $this->regionId = $regionId;
    }

    /**
     *
     * @param $lastUpdatedOn field_type           
     */
    public function setLastUpdatedOn($lastUpdatedOn)
    {
        $this->lastUpdatedOn = $lastUpdatedOn;
    }

    /**
     *
     * @param $createdOn field_type           
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;
    }

    public function __construct(array $options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }

    public function setOptions(array $options)
    {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }
}

