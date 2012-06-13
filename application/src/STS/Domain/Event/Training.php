<?php
namespace STS\Domain\Presentation\Presentation;
use STS\Domain\Entity\Entity;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @Entity
 */
class Training extends Entity
{
	protected $occuredInArea;

    protected $occuredAtLocation;
    

}