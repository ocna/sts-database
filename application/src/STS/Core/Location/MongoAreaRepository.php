<?php
namespace STS\Core\Location;

use STS\Domain\Location\AreaRepository;
use STS\Domain\Location\Region;
use STS\Domain\Location\Area;

class MongoAreaRepository implements AreaRepository
{

    private $mongoDb;
    public function __construct(\MongoDB $mongoDb)
    {
        $this->mongoDb = $mongoDb;
    }

    public function load($id)
    {
        $mongoId = new \MongoId($id);
        $areaData = $this->mongoDb->selectCollection('area')->findOne(
            array(
                '_id' => $mongoId
            )
        );
        $region = new Region();
        $region->setLegacyId($areaData['region']['legacyid'])
               ->setName($areaData['region']['name']);
        $area = new Area();

        if (!isset($areaData['legacyid'])) {
            $areaData['legacyid'] = NULL;
        }

        $area->setId($areaData['_id']->__toString())
             ->setRegion($region)
             ->setLegacyId($areaData['legacyid'])
             ->setName($areaData['name'])
             ->setState($areaData['state'])
             ->setCity($areaData['city']);
        return $area;
    }

    /**
     * delete
     *
     * @param $id string
     * @return bool
     */
    public function delete($id) {
        $results = $this->mongoDb->area->remove(
            array('_id' => new \MongoId($id)),
            array('justOne' => true, 'safe' => true)
        );

        if (1 == $results['ok']) {
            return true;
        }
        return false;
    }

    /**
     * save
     *
     * @param Area $area
     * @return Area
     */
    public function save(Area $area) {
        // new or update?
        if (is_null($area->getId())) {
            $area->markCreated();
        } else {
            $area->markUpdated();
        }

        $array = $area->toMongoArray();

        $id = array_shift($array);
        $results = $this->mongoDb->area->update(
            array('_id' => new \MongoId($id)),
            $array,
            array('upsert' => 1, 'safe' => 1)
        );

        if (array_key_exists('upserted', $results)) {
            $area->setId($results['upserted']->__toString());
        }
        return $area;
    }
}
