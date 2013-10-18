<?php
namespace STS\Core\Api;
interface LocationFacade
{
    public function getStates();

    public function getAllAreas();

    public function getAllRegions();

    public function saveArea($name, $city, $state, $region);

    public function updateArea($id, $name, $city, $state, $region);

    public function deleteArea($id);

    public function renameRegion($old_name, $new_name);

    public function getAreaById($id);

    public function searchAreasByName($term);

    public function getAreasForRegions($regions);

    public function getRegion($name);
}
