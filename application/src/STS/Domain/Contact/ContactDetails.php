<?php
namespace STS\Domain\Contact\ContactDetails;
use STS\Domain\Entity\Entity;
use Doctrine\Common\Collections\ArrayCollection;
class Address extends Entity
{
    /**
     * @OneToMany(targetEntity="Address",  
     */
    protected $addresses;
    protected $emails;
    protected $phoneNumbers;

    public function __construct()
    {
        $this->addresses = new ArrayCollection();
        $this->emails = new ArrayCollection();
        $this->phoneNumbers = new ArrayCollection();
    }
}