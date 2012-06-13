<?php
namespace STS\Domain\User\Member;
use STS\Domain\Entity\Entity;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @Entity
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="type", type="string")
 * @DiscriminatorMap({"member" = "Member", "facilitator" = "Facilitator",
 * "coordinator"="RegionalCoordinator"})
 */
class Member extends Entity
{
    /**
     * @Column
     */
    protected $firstName;
    /**
     * @Column
     */
    protected $lastName;
    /**
     * @Column
     */
    protected $type;
    /**
     * @Column(type="boolean")
     */
    
    protected $contactDetails;
    
    protected $isDeceased;
    /**
     * @ManyToMany(targetEntity="Presentation", inversedBy="attendedBy")
     * @JoinTable(name="presentations_members")
     */
    protected $presentationsAttended;

    public function __construct()
    {
        parent::__construct();
        $this->presentationsAttended = new ArrayCollection();
    }
}