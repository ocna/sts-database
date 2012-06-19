<?php
namespace STS\Domain\Contact\Contact;
use STS\Domain\Entity\Entity;
/**
 * @Entity
 * @Table(name="contact")
 */
class Contact extends Entity
{
    /**
     * @Column
     */
    protected $name;
    /**
     * @Column
     */
    protected $title;
    /**
     * @Column
     */
    protected $department;

    
    protected $contactDetails;
}