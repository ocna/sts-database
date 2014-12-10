<?php
namespace STS\Domain\Location;

interface AreaRepository
{
    public function load($id);

    public function save(Area $area);

    public function delete($id);
}
