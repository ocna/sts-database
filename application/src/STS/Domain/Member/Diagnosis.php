<?php
namespace STS\Domain\Member;

class Diagnosis
{
    private $stage;
    private $date;

     /**
      * @param string $date
      * @param string $stage
      */
    public function __construct($date, $stage)
    {
        $this->date = $date;
        $this->setStage($stage);
    }

    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    public function getDate()
    {
        return $this->date;
    }

    public static function getAvailableStages()
    {
        return array(
            'I'=>'I',
            'IA'=>'IA',
            'IB'=>'IB',
            'IC'=>'IC',
            'II'=>'II',
            'IIA'=>'IIA',
            'IIB'=>'IIB',
            'IIC'=>'IIC',
            'III'=>'III',
            'IIIA'=>'IIIA',
            'IIIB'=>'IIIB',
            'IIIC'=>'IIIC',
            'IV'=>'IV'
        );
    }
    public static function getAvailableStage($key)
    {
        if (!array_key_exists($key, static::getAvailableStages())) {
            throw new \InvalidArgumentException('No such stage with given key.');
        }
        $stages = static::getAvailableStages();
        return $stages[$key];
        return $reflected->getConstant($key);
    }
    public function setStage($stage)
    {
        if ($stage !== null && !in_array($stage, static::getAvailableStages(), true)) {
            throw new \InvalidArgumentException('No such type with given value.'. $stage);
        }
        $this->stage = $stage;
        return $this;
    }
    public function getStage()
    {
        return $this->stage;
    }
}
