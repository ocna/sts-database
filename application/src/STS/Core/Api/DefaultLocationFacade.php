<?php
namespace STS\Core\Api;
use STS\Core\Location\AreaDto;
use STS\Core\Location\RegionDto;

class DefaultLocationFacade implements LocationFacade
{

    private $mongoDb;
    public function __construct($mongoDb)
    {
        $this->mongoDb = $mongoDb;
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
    public function getAllAreas()
    {
        $areas = $this->mongoDb->area->find()->sort(array(
                'name' => 1
            ));

        $returnData = array();
        foreach ($areas as $area) {

            if(!array_key_exists('legacyid', $area)){
            $lid = null;
        }else{
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
            $returnData[] = new AreaDto($area['_id']->__toString(), $area['name'], $area['legacyid'], $area['city'],
                            $area['state'], $area['region']['name']);
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
        }else{
            $lid = $area['legacyid'];
        }
            $returnData[] = new AreaDto($area['_id']->__toString(), $area['name'], $lid, $area['city'],
                            $area['state'], $area['region']['name']);
        }
        return $returnData;
    }

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
    public static function getDefaultInstance($config)
    {
        $mongoConfig = $config->modules->default->db->mongodb;
        $auth = $mongoConfig->username ? $mongoConfig->username . ':' . $mongoConfig->password . '@' : '';
        $mongo = new \Mongo(
                        'mongodb://' . $auth . $mongoConfig->host . ':' . $mongoConfig->port . '/'
                                        . $mongoConfig->dbname);
        $mongoDb = $mongo->selectDB($mongoConfig->dbname);
        return new DefaultLocationFacade($mongoDb);
    }
}
