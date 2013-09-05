<?php
namespace STS\Core\Api;
interface LocationFacade
{
    public function getStates();

    public function getAllAreas();

    public function getAllRegions();

    public function saveArea($name, $city, $state, $region);

    public function updateArea($id, $name, $city, $state, $region);

    public function getAreaById($id);
}
