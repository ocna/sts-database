<?php

class Admin_Area extends Twitter_Bootstrap_Form_Horizontal
{
    protected $regions;
    protected $states;

    public function init()
    {
        $this->setName('areaForm');
        $this->setMethod('post');

        // area name
        $this->addElement(
            'text',
            'name',
            array(
                'label' => 'Name',
                'description' => 'Convention is to use the state abbreviation followed by city name. EX: CO-Denver',
                'dimension' => 4,
                'required' => true
            )
        );

        // city
        $this->addElement(
            'text',
            'city',
            array(
                'label' => 'City',
                'dimension' => 4,
                'required' => true
            )
        );

        // state select
        $this->addElement(
            'select',
            'state',
            array(
                'label' => 'State',
                'dimension' => 3,
                'MultiOptions' => $this->states,
                'required' => true,
                'validators' => array(new \Zend_Validate_NotEmpty(\Zend_Validate_NotEmpty::ZERO))
            )
        );

        // Regions
        $this->addElement(
            'select',
            'region',
            array(
                'label' => 'Existing Region',
                'dimension' => 3,
                'MultiOptions' => $this->regions,
                'allowEmpty' => false,
                'description' => 'Select a pre-existing regions.',
                'required' => false,
                'validators' => array(new \Admin_AreaRegionValidate()),
            )
        );

        // new region
        $this->addElement(
            'text',
            'region_new',
            array(
                'label' => 'New Region',
                'description' => 'Create a new region.',
                'dimension' => 4,
                'required' => false,
                'allowEmpty' => false,
            )
        );

        // submit button
        $this->addElement(
            'button',
            'submit',
            array(
                'label' => 'Save Area!',
                'type' => 'submit',
                'buttonType' => Twitter_Bootstrap_Form_Element_Submit::BUTTON_SUCCESS,
            )
        );

        $this
            ->addDisplayGroup(
                array('submit'),
                'actions',
                array(
                    'disableLoadDefaultDecorators' => true,
                    'decorators' => array('Actions')
                )
            );
    }

    public function setStates($states)
    {
        $this->states = $states;
    }

    public function setRegions($regions)
    {
        $this->regions = $regions;
    }
}
