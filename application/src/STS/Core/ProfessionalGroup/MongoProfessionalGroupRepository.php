<?php
namespace STS\Core\ProfessionalGroup;
use STS\Core\Location\MongoAreaRepository;
use STS\Domain\ProfessionalGroup;

class MongoProfessionalGroupRepository {
	/**
	 * @var \MongoDb
	 */
	private $mongoDb;

	/**
	 * @param \MongoDb $mongoDb
	 */
	public function __construct($mongoDb)
	{
		$this->mongoDb = $mongoDb;
	}

	/**
	 * @param ProfessionalGroup $professional_group
	 *
	 * @return ProfessionalGroup
	 */
	public function save(ProfessionalGroup $professional_group)
	{
		if (is_null($professional_group->getId())) {
			$professional_group->markCreated();
		} else {
			$professional_group->markUpdated();
		}
		$array = $this->toMongoArray($professional_group);
		$id = array_shift($array);
		$array['date'] = new \MongoDate(strtotime($array['date']));
		$results = $this->mongoDb->professionalGroup->update(
			array(
				'_id' => new \MongoId($id)
			),
			$array,
			array(
				'upsert' => 1, 'safe' => 1
			)
		);
		if (array_key_exists('upserted', $results)) {
			/** @var \MongoId $id */
			$id = $results['upserted'];
			$professional_group->setId($id->__toString());
		}
		return $professional_group;
	}

	/**
	 * load
	 *
	 * Load a single presentation
	 *
	 * @param $id
	 * @return ProfessionalGroup
	 * @throws \InvalidArgumentException
	 */
	public function load($id)
	{
		$data = $this->mongoDb->professionalGroup->findOne(
			array(
				'_id' => new \MongoId($id)
			)
		);
		if ($data == null) {
			throw new \InvalidArgumentException("ProfessionalGroup not found with given id: $id");
		}
		$professional_group = $this->mapData($data);
		return $professional_group;
	}


	/**
	 * @param string $id
	 * @return bool
	 */
	public function delete($id)
	{
		$results = $this->mongoDb->professionalGroup->remove(
			array('_id' => new \MongoId($id))
		);

		return ($results['n'] > 0);
	}

	/**
	 * @param array $criteria
	 *
	 * @return array
	 */
	public function find($criteria = array())
	{
		$professional_group_data = $this->mongoDb->professionalGroup->find($criteria)->sort(
			array(
				'name' => 1
			)
		);
		$professional_groups = array();
		if ($professional_group_data != null) {
			foreach ($professional_group_data as $data) {
				$professional_groups[] = $this->mapData($data);
			}
		}
		return $professional_groups;
	}

	/**
	 * @param array $data
	 * @return ProfessionalGroup
	 */
	private function mapData($data)
	{
		$professional_group = new ProfessionalGroup();
		if (isset($data['_id']))
		{
			/** @var \MongoId $id */
			$id = $data['_id'];
			$professional_group->setId($id->__toString());
		}
		$professional_group->setName($data['name']);
		$areaRepository = new MongoAreaRepository($this->mongoDb);
		$professional_group->setArea($areaRepository->load($data['areaId']));
		return $professional_group;
	}

	/**
	 * @param ProfessionalGroup $professional_group
	 *
	 * @return array
	 */
	public function toMongoArray($professional_group)
	{
		$array = array(
			'id'            => $professional_group->getId(),
			'name'          => $professional_group->getName(),
			'areaId'          => $professional_group->getArea()->getId(),
			'dateCreated'   => new \MongoDate($professional_group->getCreatedOn()),
			'dateUpdated'   => new \MongoDate($professional_group->getUpdatedOn())
		);

		return $array;
	}
}