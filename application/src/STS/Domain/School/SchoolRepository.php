<?php
namespace STS\Domain\School;
interface SchoolRepository
{
    public function load($id);
    
    public function find();
    
    public function save($school);
}
