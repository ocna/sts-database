<?php
namespace STS\Core\School;

class SchoolDto
{

    private $id;
    private $legacyId;
    private $name;
    public function __construct($id, $legacyId, $name)
    {
        $this->id = $id;
        $this->name = $name;
        $this->legacyId = $legacyId;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getlegacyId()
    {
        return $this->legacyId;
    }
    public function getId()
    {
        return $this->id;
    }
}
