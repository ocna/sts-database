<?php
class Admin_Model_Region
{
    protected $id;
    protected $name;
    protected $lastUpdatedOn;
    protected $createdOn;

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
     * @return the $name
     */
    public function getName()
    {
        return $this->name;
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
     * @param $name field_type           
     */
    public function setName($name)
    {
        $this->name = $name;
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

