<?php
namespace STS\Core\Location;

use STS\Domain\Location\AreaRepository;
use STS\Domain\Location\Region;
use STS\Domain\Location\Area;

class MongoAreaRepository implements AreaRepository
{

    private $mongoDb;
    public function __construct($mongoDb)
    {
        $this->mongoDb = $mongoDb;
    }

    public function load($id)
    {
        $mongoId = new \MongoId($id);
        $areaData = $this->mongoDb->area->findOne(
            array(
                '_id' => $mongoId
            )
        );
        $region = new Region();
        $region->setLegacyId($areaData['region']['legacyid'])
               ->setName($areaData['region']['name']);
        $area = new Area();
        $area->setId($areaData['_id']->__toString())
             ->setRegion($region)
             ->setLegacyId($areaData['legacyid'])
             ->setName($areaData['name'])
             ->setState($areaData['state'])
             ->setCity($areaData['city']);
        return $area;
    }
}
