<?php
namespace STS\Core\Api;
interface LocationFacade
{
    public function getStates();

    public function getAllAreas();

    public function getAllRegions();

    public function saveArea($name, $city, $state, $region);
}
