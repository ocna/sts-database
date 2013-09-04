<?php
namespace STS\Domain\Location;
use STS\Domain\Location\Area;

interface AreaRepository
{
    public function load($id);

    public function save(Area $area);
}
