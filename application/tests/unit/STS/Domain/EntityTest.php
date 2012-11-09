<?php
use STS\Domain\Entity;

class EntityTest extends \PHPUnit_Framework_TestCase
{
    const ID = '502314eec6464712c1e705cc';
    const VALID_DATE = '1352257059';
    const INVALID_DATE = '12-10-2';
    /**
     * @test
     */
    public function getValidEntity()
    {
        $entity = new Entity();
        $entity->setId(self::ID);
        $this->assertEquals(self::ID, $entity->getId());
    }
    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Id must be a string value.
     */
    public function throwExceptionForNonStringId()
    {
        $entity = new Entity();
        $id = new MongoId(self::ID);
        $entity->setId($id);
    }

    /**
     * @test
     */
    public function returnNullForDates()
    {
        $entity = new Entity();
        $this->assertNull($entity->getCreatedOn(), 'getCreatedOn did not return null');
        $this->assertNull($entity->getUpdatedOn(), 'getUpdatedOn did not return null');
    }
    
    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Argument must be unix time stamp format.
     */
    public function throwExceptionForNonTimeStampCreated()
    {
        $entity = new Entity();
        $entity->setCreatedOn(self::INVALID_DATE);
    }

    /**
     * @test
     */
    public function validSetCreatedOn()
    {
        $entity = new Entity();
        $entity->setCreatedOn(self::VALID_DATE);
        $this->assertEquals(self::VALID_DATE, $entity->getCreatedOn());
    }

    /**
     * @test
     */
    public function validMarkCreated()
    {
        $entity = new Entity();
        $entity->markCreated();
        $this->assertTrue(is_int($entity->getCreatedOn()));
        $this->assertTrue(is_int($entity->getUpdatedOn()));
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Argument must be unix time stamp format.
     */
    public function throwExceptionForNonTimeStampUpdated()
    {
        $entity = new Entity();
        $entity->setUpdatedOn(self::INVALID_DATE);
    }

    /**
     * @test
     */
    public function validSetUpdatedOn()
    {
        $entity = new Entity();
        $entity->setUpdatedOn(self::VALID_DATE);
        $this->assertEquals(self::VALID_DATE, $entity->getUpdatedOn());
    }

    /**
     * @test
     */
    public function validMarkUpdated()
    {
        $entity = new Entity();
        $entity->markUpdated();
        $this->assertTrue(is_int($entity->getUpdatedOn()));
    }


}
