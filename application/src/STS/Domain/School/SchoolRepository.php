<?php
namespace STS\Domain\School;
interface SchoolRepository
{
    public function find();
    
    public function save($school);
}
