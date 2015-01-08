<?php
namespace STS\Domain\User\Presenter;

use STS\Domain\Member;

class Presenter extends Member
{
    protected $presentsInAreas;

    public function __construct()
    {
        $this->presentsInAreas = new ArrayCollection();
    }
}
