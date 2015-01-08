<?php
namespace STS\Domain\Location;

class Address
{
    /** @var string */
    private $address;
    private $lineOne;
    private $lineTwo;
    private $city;
    private $state;
    private $zip;

    /** @deprecated */
    public function getZip()
    {
        return $this->zip;
    }

    /** @deprecated */
    public function setZip($zip)
    {
        $this->zip = $zip;
        return $this;
    }

    /** @deprecated */
    public function getState()
    {
        return $this->state;
    }

    /** @deprecated */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }

    /** @deprecated */
    public function getCity()
    {
        return $this->city;
    }

    /** @deprecated */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /** @deprecated */
    public function getLineTwo()
    {
        return $this->lineTwo;
    }

    /** @deprecated */
    public function setLineTwo($lineTwo)
    {
        $this->lineTwo = $lineTwo;
        return $this;
    }

    /** @deprecated */
    public function getLineOne()
    {
        return $this->lineOne;
    }

    /** @deprecated */
    public function setLineOne($lineOne)
    {
        $this->lineOne = $lineOne;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        // Handle legacy un-I8NLized addresses
        if (! $this->address && $this->city) {
            $this->address = <<<QQQ
{$this->lineOne}
{$this->lineTwo}
{$this->city} {$this->state} {$this->zip}
QQQ;
        }
        return $this->address;
    }

    /**
     * @param string $address
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }
}
