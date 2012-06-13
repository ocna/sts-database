<?php
namespace STS\Domain\Location\Area;
use STS\Domain\Entity\Entity;
use Doctrine\Common\Collections\ArrayCollection;
class Area extends Entity
{
    protected $name;
    protected $locations;

    public function __construct()
    {
        parent::construct();
        $this->locations = new ArrayCollection();
    }
}