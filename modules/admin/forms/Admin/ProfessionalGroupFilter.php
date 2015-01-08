<?php

class Admin_ProfessionalGroupFilter extends Twitter_Bootstrap_Form_Inline
{
	protected $regions;
	public function init()
	{
		$this->setName('professionalGroupFilterForm');
		$this->setMethod('get');
		$this->setAction('/admin/professional_group/index');

		// regions
		$this->addElement(
			'multiselect',
			'region',
			array(
				'label' => 'Region',
				'dimension' => 2,
				'MultiOptions' => $this->regions
			)
		);

		$this->addElement('button', 'update', array(
			'label' => 'Update',
			'value' => '1',
			'type' => 'submit',
			'buttonType' => 'primary'
		));

		$this->addElement('button', 'reset', array(
			'label' => 'Reset',
			'value' => '2',
			'type' => 'submit',
			'buttonType' => 'default'
		));
	}

	public function setRegions($regions)
	{
		$this->regions = $regions;
	}
}
