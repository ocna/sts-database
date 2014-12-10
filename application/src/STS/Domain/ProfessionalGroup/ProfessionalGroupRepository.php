<?php
/**
 * Created by PhpStorm.
 * User: sandysmith
 * Date: 12/5/14
 * Time: 10:36 PM
 */

namespace STS\Domain\ProfessionalGroup;


interface ProfessionalGroupRepository {
	public function save($professional_group);
	public function find($criteria);
	public function delete($id);
}