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
        $array = $presentation->toArray();
        $id = array_shift($array);
        $array['date'] = new \MongoDate(strtotime($array['date']));
        $results = $this->mongoDb->presentation
            ->update(array(
                '_id' => new \MongoId($id)
            ), $array, array(
                'upsert' => 1, 'safe' => 1
            ));
        if (array_key_exists('upserted', $results)) {
            $presentation->setId($results['upserted']->__toString());
        }
        return $presentation;
    }
}
