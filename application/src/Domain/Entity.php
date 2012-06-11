<?php
namespace STS\Domain\Entity;
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
}