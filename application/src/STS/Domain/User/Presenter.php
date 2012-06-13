<?php
namespace STS\Domain\User\Presenter;
use STS\Domain\User\Member\Member;
use Doctrine\Common\Collections\ArrayCollection;
class Presenter extends Member
{
    protected $presentsInAreas;

    public function __construct()
    {
        parent::__construct();
        $this->presentsInAreas = new ArrayCollection();
    }
}