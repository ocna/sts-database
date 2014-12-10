<?php
namespace STS\Domain\ProfessionalGroup;

interface ProfessionalGroupRepository
{
    public function save($professional_group);
    public function find($criteria);
    public function delete($id);
}
