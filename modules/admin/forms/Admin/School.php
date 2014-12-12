<?php

class Admin_School extends Twitter_Bootstrap_Form_Horizontal
{
    protected $schoolTypes;
    protected $areas;
    protected $states;

    public function init()
    {
        $this->setName('schoolForm');
        $this->setMethod('post');
        //name
        $this
            ->addElement('text', 'name', array(
                'label' => 'Name of School', 'dimension' => 4, 'required' => true
            ));
        //area
        $this
            ->addElement('select', 'area', array(
                    'label' => 'Area', 'dimension' => 2, 'MultiOptions' => $this->areas, 'required' => true,
                    'validators' => array(
                        new \Zend_Validate_NotEmpty(\Zend_Validate_NotEmpty::ZERO)
                    ), 'description' => 'The Region for this School will be automatically updated based on the area.'
            ));
        //type
        $this
            ->addElement('select', 'schoolType', array(
                    'label' => 'Program Type', 'dimension' => 2, 'MultiOptions' => $this->schoolTypes, 'required' => true,
                    'validators' => array(
                        new \Zend_Validate_NotEmpty(\Zend_Validate_NotEmpty::ZERO)
                    )
            ));
        //inactive
        $this
            ->addElement('checkbox', 'isInactive', array(
                'label' => 'Inactive?',
            ));
        //notes
        $this
            ->addElement('textarea', 'notes', array(
                'label' => 'Notes', 'dimension' => 4, 'rows' => 5
            ));
        //address
        $this
            ->addElement('text', 'addressLineOne', array(
                'label' => 'Address 1', 'dimension' => 4, 'required' => true
            ));
        $this
            ->addElement('text', 'addressLineTwo', array(
                'label' => 'Address 2', 'dimension' => 4, 'required' => false
            ));
        $this
            ->addElement('text', 'city', array(
                'label' => 'City', 'dimension' => 3, 'required' => true
            ));
        $this
            ->addElement('select', 'state', array(
                    'label' => 'State', 'dimension' => 3, 'MultiOptions' => $this->states, 'required' => true,
                    'validators' => array(
                        new \Zend_Validate_NotEmpty(\Zend_Validate_NotEmpty::ZERO)
                    )
            ));
        $this
            ->addElement('text', 'zip', array(
                'label' => 'Zip', 'dimension' => 3, 'required' => true
            ));
        $this
            ->addDisplayGroup(array(
                'addressLineOne', 'addressLineTwo', 'city', 'state', 'zip'
            ), 'address', array(
                'legend' => 'Address'
            ));
        //Saving
        $this
            ->addElement('button', 'submit', array(
                    'label' => 'Save School!', 'type' => 'submit',
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
    public function setSchoolTypes($schoolTypes)
    {
        $this->schoolTypes = $schoolTypes;
    }
    public function setAreas($areas)
    {
        $this->areas = $areas;
    }
    public function setStates($states)
    {
        $this->states = $states;
    }
}
