<?php
namespace STS\Domain;

use STS\Domain\Location\Area;

class ProfessionalGroup extends Entity implements HasArea
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Area
     */
    private $area;

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param Area $area
     *
     * @return $this
     */
    public function setArea($area)
    {
        $this->area = $area;
        return $this;
    }

    /**
     * @return Area
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * @return mixed
     */
    public function getAreaName()
    {
        return $this->area->getName();
    }

    /**
     * @return mixed
     */
    public function getRegionName()
    {
        return $this->area->getRegion()->getName();
    }
}
