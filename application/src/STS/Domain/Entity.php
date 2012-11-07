<?php
namespace STS\Domain;

class Entity
{

    protected $id = null;
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
}
