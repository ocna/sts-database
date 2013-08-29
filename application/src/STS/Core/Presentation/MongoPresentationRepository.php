<?php
namespace STS\Core\Presentation;

use STS\Domain\Presentation;
use STS\Domain\Survey;
use STS\Domain\Presentation\PresentationRepository;
use STS\Core\Member\MongoMemberRepository;
use STS\Core\School\MongoSchoolRepository;
use STS\Domain\Survey\Template;

class MongoPresentationRepository implements PresentationRepository
{
    private $mongoDb;

    /**
     * __construct
     *
     * @param $mongoDb
     */
    public function __construct($mongoDb)
    {
        $this->mongoDb = $mongoDb;
    }

    /**
     * save
     *
     * @param $presentation
     * @return Presentation
     * @throws \InvalidArgumentException
     */
    public function save($presentation)
    {
        if (!$presentation instanceof Presentation) {
            throw new \InvalidArgumentException('Instance of Presentation expected.');
        }
        if (is_null($presentation->getId())) {
            $presentation->markCreated();
        } else {
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
     * load
     *
     * Load a single presentation
     *
     * @param $id
     * @return Presentation
     * @throws \InvalidArgumentException
     */
    public function load($id)
    {
        $data = $this->mongoDb->presentation->findOne(
            array(
                '_id' => new \MongoId($id)
            )
        );
        if ($data == null) {
            throw new \InvalidArgumentException("Presentation not found with given id: $id");
        }
        $presentation = $this->mapData($data);
        return $presentation;
    }

     /**
      * find
      *
      * @param array $criteria
      */
    public function find($criteria = array())
    {
        $presentationData = $this->mongoDb->presentation->find($criteria)->sort(
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

    /**
     * mapData
     *
     * @param $data
     * @return Presentation
     */
    private function mapData($data)
    {
        $presentation = new Presentation();
        $presentation->setId($data['_id']->__toString())
                     ->setNumberOfParticipants($data['nparticipants'])
                     ->setDate(date('Y-M-d h:i:s', $data['date']->sec))
                     ->setNotes($data['notes'])
                     ->setNumberOfFormsReturnedPost($data['nforms'])
                     ->setEnteredByUserId($data['entered_by_user_id'])
                     ->setType($data['type']);
        if (array_key_exists('nformspre', $data)) {
            $presentation->setNumberOfFormsReturnedPre($data['nformspre']);
        }
        if (array_key_exists('survey_id', $data)) {
            $template = new Template();
            $survey = $template->createSurveyInstance();
            $survey->setId($data['survey_id']);
            $presentation->setSurvey($survey);
        }
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

    /**
     * updateEnteredBy
     *
     * @param $old
     * @param $new
     */
    public function updateEnteredBy($old, $new)
    {
        $results = $this->mongoDb->presentation->update(
            array('entered_by_user_id' => $old),
            array('$set' => array('entered_by_user_id' => $new)),
            array(
                'multiple' => 1
            )
        );

        return $results;
    }
}
