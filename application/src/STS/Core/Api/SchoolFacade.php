<?php
namespace STS\Core\Api;
interface SchoolFacade
{
    public function getSchoolsForSpecification($spec);

    public function getSchoolTypes();

    public function saveSchool($name, $areaId, $schoolType, $notes, $addressLineOne, $addressLineTwo, $city, $state, $zip);

    public function getSchoolById($id);
}
