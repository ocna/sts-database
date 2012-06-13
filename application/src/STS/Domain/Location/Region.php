<?php
namespace STS\Domain\Location\Region;
use STS\Domain\Entity\Entity;
use Doctrine\Common\Collections\ArrayCollection;
class Region extends Entity
{
    protected $name;
    protected $areas;

    public function __construct()
    {
        parent::construct();
        $this->areas = new ArrayCollection();
    }
}