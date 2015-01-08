<?php
namespace STS\Domain;

use STS\Domain\Location\Area;

interface HasArea {
    /**
     * @return string
     */
    public function getId();

    /**
     * @param Area $area
     * @return HasArea
     */
    public function setArea($area);

    /**
     * @return Area
     */
    public function getArea();
}