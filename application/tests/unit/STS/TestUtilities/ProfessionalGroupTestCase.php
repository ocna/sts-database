<?php
/**
 * Created by PhpStorm.
 * User: sandysmith
 * Date: 12/5/14
 * Time: 9:19 PM
 */

namespace STS\TestUtilities;
use STS\Domain\ProfessionalGroup;
use STS\TestUtilities\Location\AreaTestCase;

class ProfessionalGroupTestCase extends \PHPUnit_Framework_TestCase {
	const ID = '5482696bf0997d35e8be3993';
	const NAME = 'Test Professional Group';

	/**
	 * @return ProfessionalGroup
	 */
	protected function getValidProfessionalGroup()
	{
		$professional_group = new ProfessionalGroup();
		$area = AreaTestCase::createValidArea();
		$professional_group->setId(self::ID)
			->setName(self::NAME)
			->setArea($area);
		return $professional_group;
	}

	/**
	 * @return ProfessionalGroup
	 */
	public static function createValidProfessionalGroup()
	{
		$professional_group_test = new ProfessionalGroupTestCase();
		return $professional_group_test->getValidProfessionalGroup();
	}

	/**
	 * @param ProfessionalGroup $professional_group
	 */
	protected function assertValidProfessionalGroup($professional_group)
	{
		$this->assertInstanceOf('STS\Domain\ProfessionalGroup', $professional_group);
		$this->assertEquals(self::ID, $professional_group->getId());
		$this->assertEquals(self::NAME, $professional_group->getName());
		$this->assertInstanceOf('STS\Domain\Location\Area', $professional_group->getArea());
	}
}