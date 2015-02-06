<?php
namespace STS\Core\Api;

interface SchoolFacade
{
    /**
     * @param \STS\Domain\Location\Specification\MemberLocationSpecification $spec
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
        $address
    );

    public function updateSchool(
        $id,
        $name,
        $areaId,
        $schoolType,
        $isInactive,
        $notes,
        $address
    );

    public function getSchoolById($id);
}
