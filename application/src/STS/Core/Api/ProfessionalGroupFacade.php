<?php
namespace STS\Core\Api;

use STS\Domain\ProfessionalGroup;

interface ProfessionalGroupFacade
{
    /**
     * @param ProfessionalGroup $professional_group
     *
     * @return mixed
     */
    public function saveProfessionalGroup($professional_group);

    /**
     * @param ProfessionalGroup $professional_group
     *
     * @return mixed
     */
    public function updateProfessionalGroup($professional_group);

    /**
     * @return mixed
     */
    public function getAllProfessionalGroups();

    /**
     * @param array $criteria
     *
     * @return mixed
     */
    public function getProfessionalGroupsMatching($criteria);

    /**
     * @param string $id
     * @return mixed
     */
    public function getProfessionalGroupById($id);

    /**
     * @param string $id
     * @return boolean
     */
    public function deleteProfessionalGroup($id);
}
