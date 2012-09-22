<?php
namespace STS\Core\Location;

class RegionDto
{

    private $legacyId;
    private $name;
    public function __construct($legacyId, $name)
    {
        $this->legacyId = $legacyId;
        $this->name = $name;
    }
    public function getLegacyId()
    {
        return $this->legacyId;
    }
    public function getName()
    {
        return $this->name;
    }
}
