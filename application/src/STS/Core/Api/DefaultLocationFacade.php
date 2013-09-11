<?php
namespace STS\Core\Api;

use STS\Core\Location\AreaDto;
use STS\Core\Location\RegionDto;
use STS\Domain\Location\Area;
use STS\Core\Location\MongoAreaRepository;

class DefaultLocationFacade implements LocationFacade
{
    private $mongoDb;
    protected $areaRepository;

    public function __construct($mongoDb, $areaRepository)
    {
        $this->mongoDb = $mongoDb;
        $this->areaRepository = $areaRepository;
    }

    public function getStates()
    {
        return array(
                'AL' => "Alabama", 'AK' => "Alaska", 'AZ' => "Arizona", 'AR' => "Arkansas", 'CA' => "California",
                'CO' => "Colorado", 'CT' => "Connecticut", 'DE' => "Delaware", 'DC' => "District Of Columbia",
                'FL' => "Florida", 'GA' => "Georgia", 'HI' => "Hawaii", 'ID' => "Idaho", 'IL' => "Illinois",
                'IN' => "Indiana", 'IA' => "Iowa", 'KS' => "Kansas", 'KY' => "Kentucky", 'LA' => "Louisiana",
                'ME' => "Maine", 'MD' => "Maryland", 'MA' => "Massachusetts", 'MI' => "Michigan", 'MN' => "Minnesota",
                'MS' => "Mississippi", 'MO' => "Missouri", 'MT' => "Montana", 'NE' => "Nebraska", 'NV' => "Nevada",
                'NH' => "New Hampshire", 'NJ' => "New Jersey", 'NM' => "New Mexico", 'NY' => "New York",
                'NC' => "North Carolina", 'ND' => "North Dakota", 'OH' => "Ohio", 'OK' => "Oklahoma", 'OR' => "Oregon",
                'PA' => "Pennsylvania", 'RI' => "Rhode Island", 'SC' => "South Carolina", 'SD' => "South Dakota",
                'TN' => "Tennessee", 'TX' => "Texas", 'UT' => "Utah", 'VT' => "Vermont", 'VI' => 'Virgin Islands',
                'VA' => "Virginia", 'WA' => "Washington", 'WV' => "West Virginia", 'WI' => "Wisconsin",
                'WY' => "Wyoming"
        );
    }

    /**
     * getAreaById
     *
     * @param $id
     * @return AreaDto|boolean
     */
    public function getAreaById($id)
    {
        if ($area = $this->areaRepository->load($id)) {
            return AreaDto::assembleFromArea($area);
        }

        // not found
        return false;
    }

    /**
     * getAllAreas
     *
     * @return array
     */
    public function getAllAreas()
    {
        $areas = $this->mongoDb->area->find()->sort(array(
                'name' => 1
            ));

        $returnData = array();
        foreach ($areas as $area) {
            if (!array_key_exists('legacyid', $area)){
                $lid = null;
            } else {
                $lid = $area['legacyid'];
            }

            $returnData[] = new AreaDto($area['_id']->__toString(), $area['name'], $lid, $area['city'],
                            $area['state'], $area['region']['name']);
        }
        return $returnData;
    }

    public function searchAreasByName($term)
    {
        $regex = new \MongoRegex("/$term/i");
        $areas = $this->mongoDb->area->find(array(
                'name' => $regex
            ))->sort(array(
                'name' => 1
            ));
        $returnData = array();
        foreach ($areas as $area) {

            if (!isset($area['legacyid'])) {
                $area['legacyid'] = null;
            }
            $returnData[] = new AreaDto(
                $area['_id']->__toString(),
                $area['name'],
                $area['legacyid'],
                $area['city'],
                $area['state'],
                $area['region']['name']
            );
        }
        return $returnData;
    }

    public function getAreasForRegions($regions)
    {
        $query = array('region.name'=>array('$in'=> $regions));
        $areas = $this->mongoDb->area->find($query)->sort(array(
                'name' => 1
            ));
        $returnData = array();

        foreach ($areas as $area) {
            if(!array_key_exists('legacyid', $area)){
                $lid = null;
            } else {
                $lid = $area['legacyid'];
            }
            $returnData[] = new AreaDto(
                $area['_id']->__toString(),
                $area['name'],
                $lid,
                $area['city'],
                $area['state'],
                $area['region']['name']
            );
        }
        return $returnData;
    }

    /**
     * getAllRegions
     *
     * @return array
     */
    public function getAllRegions()
    {
        $regions = $this->mongoDb->area->distinct('region.name');
        sort($regions);
        $returnData = array();
        foreach ($regions as $region) {
            $returnData[] = new RegionDto(null, $region);
        }
        return $returnData;
    }

    /**
     * getRegion
     *
     * Lookup a region by name
     *
     * @param $name
     * @return RegionDto
     */
    public function getRegion($name)
    {
        if ($region = $this->mongoDb->area->findOne(array('region.name' => "$name"))) {
            return new RegionDto($region['region']['legacyid'], $region['region']['name']);
        }
    }

    public static function getDefaultInstance($config)
    {
        // get the mongo instance
        $mongoConfig = $config->modules->default->db->mongodb;
        $auth = $mongoConfig->username ? $mongoConfig->username . ':' . $mongoConfig->password . '@' : '';
        $mongo = new \Mongo(
                        'mongodb://' . $auth . $mongoConfig->host . ':' . $mongoConfig->port . '/'
                                        . $mongoConfig->dbname);
        $mongoDb = $mongo->selectDB($mongoConfig->dbname);

        $areaRepository = new MongoAreaRepository($mongoDb);
        return new DefaultLocationFacade($mongoDb, $areaRepository);
    }

    /**
     * saveArea
     *
     * Save a region to the data store
     *
     * @param $name
     * @param $city
     * @param $state
     * @param $region
     * @return mixed
     */
    public function saveArea($name, $city, $state, $region)
    {
        $area = new Area;
        $area->setName($name);
        $area->setCity($city);
        $area->setState($state);
        $area->setRegion($region);

        $savedArea = $this->areaRepository->save($area);
        return AreaDto::assembleFromArea($savedArea);
    }

    /**
     * updateArea
     *
     * Update an existing area
     *
     * @param $id
     * @param $name
     * @param $city
     * @param $state
     * @param $region
     * @return mixed
     */
    public function updateArea($id, $name, $city, $state, $region)
    {
        $area = new Area;
        $area->setId($id);
        $area->setName($name);
        $area->setCity($city);
        $area->setState($state);
        $area->setRegion($region);

        $savedArea = $this->areaRepository->save($area);
        return AreaDto::assembleFromArea($savedArea);
    }

    /**
     * renameRegion
     *
     * Renames an existing region
     *
     * @param $old_name
     * @param $new_name
     * @return RegionDto
     */
    public function renameRegion($old_name, $new_name)
    {
        if ($areas = $this->mongoDb->area->find(array('region.name' => $old_name))) {

            // prepare a regionDTO and keep legacyId
            $old_dto = $this->getRegion($old_name);
            $new_dto = new RegionDto($old_dto->getLegacyId(), $new_name);

            // update all areas pointing to the old region with the new region name
            foreach ($areas as $area) {
                $this->updateArea(
                    $area['_id']->__toString(),
                    $area['name'],
                    $area['city'],
                    $area['state'],
                    $new_dto);
            }

            return $new_dto;
        }
    }
}
