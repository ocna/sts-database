<?php
namespace STS\Domain\Presentation\Presentation;
use STS\Domain\Entity\Entity;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @Entity
 */
class Presentation extends Entity
{
    /**
     * @ManyToMany(targetEntity="Member", mappedBy="attendedBy")
     */
    protected $attendedBy;
    /**
     * @ManyToOne(targetEntity="Area")
     * @JoinColumn(name="area_id", referencedColumnName="id")
     */
    protected $area;
    /**
     * @Column
     */
    protected $type;
    /**
     * @Column(type="integer")
     */
    protected $numberOfStudents;

    protected $occuredAt;
    
    public function __construct()
    {
        $this->attendedBy = new ArrayCollection();
    }
}