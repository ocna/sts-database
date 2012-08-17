<?php
namespace STS\Core\Presentation;
use STS\Domain\Presentation;
use STS\Domain\Presentation\PresentationRepository;

class MongoPresentationRepository implements PresentationRepository
{

    private $mongoDb;
    public function __construct($mongoDb)
    {
        $this->mongoDb = $mongoDb;
    }
    public function save($presentation)
    {
        if (!$presentation instanceof Presentation) {
            throw new \InvalidArgumentException('Instance of Presentation expected.');
        }
        
        
        
        
        
        
    }
}
