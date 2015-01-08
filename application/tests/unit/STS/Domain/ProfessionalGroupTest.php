<?php
namespace STS\Domain;

use STS\TestUtilities\ProfessionalGroupTestCase;

class ProfessionalGroupTest extends ProfessionalGroupTestCase {
	/**
	 * @test
	 */
	public function validProfessionalGroup()
	{
		$professional_group = $this->getValidProfessionalGroup();
		$this->assertValidProfessionalGroup($professional_group);
	}
}