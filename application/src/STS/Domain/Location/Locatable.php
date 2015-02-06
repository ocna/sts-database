<?php
namespace STS\Domain\Location;

interface Locatable {
    /**
     * @return string
     */
    public function getId();

    /**
     * @param Area $area
     * @return Locatable
     */
    public function setArea($area);

    /**
     * @return Area
     */
    public function getArea();

    /**
     * @return Address
     */
    public function getAddress();
}