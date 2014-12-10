<?php

class Admin_ProfessionalGroup extends Twitter_Bootstrap_Form_Horizontal
{
	protected $areas;

	public function init()
	{
		$this->setName('professionalGroupForm');
		$this->setMethod('post');
		//name
		$this
			->addElement('text', 'name', array(
				'label' => 'Name of Professional Group', 'dimension' => 4, 'required' => true
			));
		//area
		$this
			->addElement('select', 'area', array(
				'label' => 'Area', 'dimension' => 2, 'MultiOptions' => $this->areas, 'required' => true,
				'validators' => array(
					new \Zend_Validate_NotEmpty(\Zend_Validate_NotEmpty::ZERO)
				), 'description' => 'The Region for this School will be automatically updated based on the area.'
			));
		//Saving
		$this
			->addElement('button', 'submit', array(
				'label' => 'Save Professional Group!', 'type' => 'submit',
				'buttonType' => Twitter_Bootstrap_Form_Element_Submit::BUTTON_SUCCESS
			));
		$this
			->addDisplayGroup(array(
				'submit'
			), 'actions', array(
				'disableLoadDefaultDecorators' => true,
				'decorators' => array(
					'Actions'
				)
			));
	}
	public function setAreas($areas)
	{
		$this->areas = $areas;
	}
}
