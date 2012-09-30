<?php
use STS\Domain\EntityWithTypes;

class EntityWithTypesTest extends \PHPUnit_Framework_TestCase
{
    const ID = '502314eec6464712c1e705cc';
    const NOT_TYPE = 'NOT_TYPE';
    const NOT_AVAILABLE_TYPE = 'TYPE_NOT_AVAIL';
    const NOT_AVAILABLE_TYPE_VALUE = 'Type Not Avail';
    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage No such type with given value.
     */
    public function throwExceptionForNotFoundTypeValue()
    {
        $entity = new EntityWithTypes();
        $entity->setType('Type Not Avail');
    }
    /**
     * @test
     */
    public function validCreateObject()
    {
        $entity = new EntityWithTypes();
        $entity->setId(self::ID);
        $this->assertEquals(self::ID, $entity->getID());
        $this->assertEquals(null, $entity->getType());
    }
    /**
     * @test
     */
    public function allowSettingTypeToNull()
    {
        $entity = new EntityWithTypes();
        $object = $entity->setType(null);
        $this->assertEquals(null, $entity->getType());
        $this->assertEquals($entity, $object);
    }
    /**
     * @test
     */
    public function returnNothingForGetAvailableTypes()
    {
        $types = EntityWithTypes::getAvailableTypes();
        $this->assertTrue(is_array($types), 'Returned types is not an array.');
        $this->assertEmpty($types, 'Returned types is not empty.');
    }
    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage No such type with given key.
     */
    public function throwExceptionForNotFoundTypeKey()
    {
        EntityWithTypes::getAvailableType(self::NOT_AVAILABLE_TYPE);
    }
    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Type key must begin with "TYPE_".
     */
    public function throwExceptionForInvalidTypeKey()
    {
        EntityWithTypes::getAvailableType(self::NOT_TYPE);
    }
}
