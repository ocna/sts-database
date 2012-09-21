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
            $returnData[] = new AreaDto($area['_id']->__toString(), $area['name'], $area['legacyid'], $area['city'],
                            $area['state'], $area['region']['name']);
        }
        return $returnData;
    }
    public function getAllRegions()
    {
        $regions = $this->mongoDb->area->distinct('region');
        $returnData = array();
        foreach ($regions as $region) {
            $returnData[] = new RegionDto($region['legacyid'], $region['name']);
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
