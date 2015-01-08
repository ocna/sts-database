<?php
namespace STS\Domain\User\Facilitator;

use STS\Domain\User\Presenter\Presenter;

class Facilitator extends Presenter
{
    protected $facilitatesForAreas;

    public function __construct()
    {
        parent::__construct();
        $this->facilitatesForAreas = new ArrayCollection();
    }
}
