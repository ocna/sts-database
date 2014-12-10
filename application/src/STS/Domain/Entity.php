<?php
namespace STS\Domain;

class Entity
{

    protected $id = null;
    protected $createdOn = null;
    protected $updatedOn = null;

    public function getId()
    {
        return $this->id;
    }
    public function setId($id)
    {
        if (is_object($id)) {
            throw new \InvalidArgumentException('Id must be a string value.');
        }
        $this->id = $id;
        return $this;
    }

    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $this->validateTime($createdOn);
        return $this;
    }

    public function markCreated()
    {
        $this->setCreatedOn(time());
        $this->markUpdated();
    }

    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    public function setUpdatedOn($updatedOn)
    {
        $this->updatedOn = $this->validateTime($updatedOn);
        return $this;
    }

    public function markUpdated()
    {
        $this->setUpdatedOn(time());
    }

    private function validateTime($time)
    {
        if (is_null($time)) {
            return null;
        }
        if (! is_numeric($time)) {
            throw new \InvalidArgumentException('Argument must be unix time stamp format. ' . $time);
        }
        if ($time < 0) {
            return null;
        }
        return $time;
    }
}
