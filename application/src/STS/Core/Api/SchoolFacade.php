<?php
namespace STS\Core\Api;

interface SchoolFacade
{
    /**
     * @param \STS\Domain\School\Specification\MemberSchoolSpecification $spec
     * @return mixed
     */
    public function getSchoolsForSpecification($spec);

    public function getSchoolTypes();

    public function saveSchool(
        $name,
        $areaId,
        $schoolType,
        $isInactive,
        $notes,
        $addressLineOne,
        $addressLineTwo,
        $city,
        $state,
        $zip
    );

    public function updateSchool(
        $id,
        $name,
        $areaId,
        $schoolType,
        $isInactive,
        $notes,
        $addressLineOne,
        $addressLineTwo,
        $city,
        $state,
        $zip
    );

    public function getSchoolById($id);
}
