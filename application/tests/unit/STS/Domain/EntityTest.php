<?php
use STS\Domain\Entity;

class EntityTest extends \PHPUnit_Framework_TestCase
{
    const ID = '502314eec6464712c1e705cc';
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
}
