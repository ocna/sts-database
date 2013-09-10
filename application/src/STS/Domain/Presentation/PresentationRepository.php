<?php
namespace STS\Domain\Presentation;

interface PresentationRepository
{
    public function save($presentation);
    public function find($criteria);
    public function delete($id);
}
