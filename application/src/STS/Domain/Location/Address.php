<?php
namespace STS\Domain\Location;

class Address
{

    private $lineOne;
    private $lineTwo;
    private $city;
    private $state;
    private $zip;
    public function getZip()
    {
        return $this->zip;
    }
    public function setZip($zip)
    {
        $this->zip = $zip;
        return $this;
    }
    public function getState()
    {
        return $this->state;
    }
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }
    public function getCity()
    {
        return $this->city;
    }
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }
    public function getLineTwo()
    {
        return $this->lineTwo;
    }
    public function setLineTwo($lineTwo)
    {
        $this->lineTwo = $lineTwo;
        return $this;
    }
    public function getLineOne()
    {
        return $this->lineOne;
    }
    public function setLineOne($lineOne)
    {
        $this->lineOne = $lineOne;
        return $this;
    }
}
