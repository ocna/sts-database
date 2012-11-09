<?php
namespace STS\Core\Presentation;

use STS\Domain\Presentation;
use STS\Domain\Presentation\PresentationRepository;
use STS\Core\Member\MongoMemberRepository;
use STS\Core\School\MongoSchoolRepository;

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
        if(is_null($presentation->getId())){
            $presentation->markCreated();
        }else{
            $presentation->markUpdated();
        }
        $array = $presentation->toMongoArray();
        $id = array_shift($array);
        $array['date'] = new \MongoDate(strtotime($array['date']));
        $results = $this->mongoDb->presentation->update(
            array(
                '_id' => new \MongoId($id)
            ),
            $array,
            array(
                'upsert' => 1, 'safe' => 1
            )
        );
        if (array_key_exists('upserted', $results)) {
            $presentation->setId($results['upserted']->__toString());
        }
        return $presentation;
    }

     /**
      * @param array $criteria
      */
    public function find($criteria = null)
    {
        $presentationData = $this->mongoDb->presentation->find()->sort(
            array(
                'date' => -1
            )
        );
        $presentations = array();
        if ($presentationData != null) {
            foreach ($presentationData as $data) {
                $presentations[] = $this->mapData($data);
            }
        }
        return $presentations;
    }

    private function mapData($data)
    {
        $presentation = new Presentation();
        $presentation->setId($data['_id']->__toString())
                     ->setNumberOfParticipants($data['nparticipants'])
                     ->setDate(date('Y-M-d h:i:s', $data['date']->sec))
                     ->setNotes($data['notes'])
                     ->setNumberOfFormsReturned($data['nforms'])
                     ->setEnteredByUserId($data['entered_by_user_id'])
                     ->setType($data['type']);
        $schoolRepository = new MongoSchoolRepository($this->mongoDb);
        $presentation->setLocation($schoolRepository->load($data['school_id']));
        $memberRepository = new MongoMemberRepository($this->mongoDb);
        $members = array();
        foreach ($data['members'] as $memberId) {
            $members[] = $memberRepository->load($memberId);
        }
        $presentation->setMembers($members);
        return $presentation;
    }
}
