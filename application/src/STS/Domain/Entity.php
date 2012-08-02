<?php
namespace STS\Domain;
/**
 * @MappedSuperclass
 */
class Entity
{
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;
    /**
     * @Column(type="datetime")
     */
    protected $lastUpdatedOn;
    /**
     * @Column(type="datetime")
     */
    protected $createdOn;

    public function getId()
    {
        return $this->id;
    }

    public function getLastUpdatedOn()
    {
        return $this->lastUpdatedOn;
    }

    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setLastUpdatedOn($lastUpdatedOn)
    {
        $this->lastUpdatedOn = $lastUpdatedOn;
        return $this;
    }

    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;
        return $this;
    }
}