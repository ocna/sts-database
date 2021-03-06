<?php
namespace STS\Core\Api;

use STS\Core\Location\MongoAreaRepository;
use STS\Domain\Location\Specification\MemberLocationSpecification;
use STS\Core\ProfessionalGroup\MongoProfessionalGroupRepository;
use STS\Domain\Location\AreaRepository;
use STS\Domain\ProfessionalGroup;

class DefaultProfessionalGroupFacade implements ProfessionalGroupFacade
{
    /**
     * @var MongoProfessionalGroupRepository
     */
    private $professionalGroupRepository;

    /**
     * @var AreaRepository
     */
    private $areaRepository;

    /**
     * @param MongoProfessionalGroupRepository $professional_group_repository
     * @param AreaRepository $area_repository
     */
    public function __construct(
        MongoProfessionalGroupRepository $professional_group_repository,
        AreaRepository $area_repository
    ) {
        $this->professionalGroupRepository = $professional_group_repository;
        $this->areaRepository = $area_repository;
    }

    /**
     * @param \STS\Domain\ProfessionalGroup $professional_group
     *
     * @return mixed|\STS\Domain\ProfessionalGroup
     */
    public function saveProfessionalGroup($professional_group)
    {
        return $this->professionalGroupRepository->save($professional_group);
    }

    /**
     * @param ProfessionalGroup $professional_group
     *
     * @return mixed|ProfessionalGroup
     */
    public function updateProfessionalGroup($professional_group)
    {
        return $this->professionalGroupRepository->save($professional_group);
    }

    /**
     * @param string $id
     *
     * @return mixed|ProfessionalGroup
     */
    public function getProfessionalGroupById($id)
    {
        return $this->professionalGroupRepository->load($id);
    }

    public function getProfessionalGroupsMatching($criteria)
    {
        return $this->professionalGroupRepository->find($criteria);
    }

    /**
     * @return array|mixed
     */
    public function getAllProfessionalGroups()
    {
        $professional_groups = $this->professionalGroupRepository->find();
        return $professional_groups;
    }

    /**
     * filterByRegions
     *
     * @param $professional_groups
     * @param $regions
     *
*@return array
     */
    public function filterByRegions($professional_groups, $regions)
    {
        if (!is_array($regions)) {
            $regions = (array) $regions;
        }
        // remove empty values
        $regions = array_filter($regions);
        if (empty($regions)) {
            return $professional_groups;
        }

        // look for matches
        $professional_groups = array_filter(
            $professional_groups,
            function (ProfessionalGroup $presentation) use ($regions) {
                $area = $presentation->getArea();
                return in_array($area->getRegion()->getName(), $regions);
            }
        );

        return $professional_groups;
    }


    /**
     * filterByStates
     *
     * @param $professional_groups
     * @param $states
     * @return array
     */
    public function filterByStates($professional_groups, $states)
    {
        if (!is_array($states)) {
            $states = (array) $states;
        }

        // remove empty values
        $states = array_filter($states);
        if (empty($states)) {
            return $professional_groups;
        }

        // look for matches
        $professional_groups = array_filter(
            $professional_groups,
            function (ProfessionalGroup $professional_group) use ($states) {
                $area = $professional_group->getArea();
                return in_array($area->getState(), $states);
            }
        );

        return $professional_groups;
    }

    /**
     * @param array $professional_groups
     * @param $areas
     *
     * @return array
     */
    public function filterByAreas($professional_groups, $areas)
    {
        if (!is_array($areas)) {
            $areas = (array) $areas;
        }

        // remove empty values
        $areas = array_filter($areas);
        if (empty($areas)) {
            return $professional_groups;
        }

        // look for matches
        $professional_groups = array_filter(
            $professional_groups,
            function (ProfessionalGroup $professional_group) use ($areas) {
                return in_array($professional_group->getArea()->getId(), $areas);
            }
        );

        return $professional_groups;
    }

    /**
     * getProfessionalGroupsForSpecification
     *
     * NOTE: not sure where this is used.
     *
     * @param MemberLocationSpecification $spec
     * @return array
     */
    public function getProfessionalGroupsForSpecification($spec = null)
    {
        $allProfessionalGroups = $this->professionalGroupRepository->find();
        if ($spec !== null) {
            $professionalGroups = array();
            foreach ($allProfessionalGroups as $professionalGroup) {
                if ($spec->isSatisfiedBy($professionalGroup)) {
                    $professionalGroups[] = $professionalGroup;
                }
            }
        } else {
            $professionalGroups = $allProfessionalGroups;
        }

        return $professionalGroups;
    }


    /**
     * @param $mongoDb
     * @return DefaultProfessionalGroupFacade
     */
    public static function getDefaultInstance($mongoDb)
    {
        $professional_group_repository = new MongoProfessionalGroupRepository($mongoDb);
        $area_repository = new MongoAreaRepository($mongoDb);
        return new DefaultProfessionalGroupFacade($professional_group_repository, $area_repository);
    }

    /**
     * @param $id
     * @return bool
     */
    public function deleteProfessionalGroup($id)
    {
        $presentation = $this->professionalGroupRepository->load($id);
        return $this->professionalGroupRepository->delete($presentation->getId());
    }
}
