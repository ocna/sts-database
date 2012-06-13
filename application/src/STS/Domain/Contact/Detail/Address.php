<?php
namespace STS\Domain\Contact\Detail\Address;
use STS\Domain\Entity\Entity;
class Address extends Entity
{
    protected $type;
    protected $lineOne;
    protected $lineTwo;
    protected $city;
    protected $state;
    protected $zip;
}