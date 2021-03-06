<?php

class Admin_ReportBasicForm extends Twitter_Bootstrap_Form_Vertical
{
    protected $regions = array();
    protected $states  = array();
    protected $members = array();
    protected $schoolTypes = array();

    public function init()
    {
        $date_validator = new Zend_Validate_Date(array('format' => 'MM/dd/yyyy'));

        $this->setName('reportBasicForm');
        $this->setMethod('get');
        $this->setAction('/admin/report/presentation');
        $this->addElement(
            'text',
            'startDate',
            array(
                'label' => 'Starting Date',
                'placeholder'=>'Starting Date',
                'dimension' => 2,
                'validators' => array($date_validator),
                'append' => array(
                    'name' => 'startDateButton',
                    'label' => '',
                    'icon' => 'calendar',
                ),
                'attribs' => array(
                    'required' => 'required'
                ),
            )
        );

        $this->addElement(
            'text',
            'endDate',
            array(
                'label' => 'Ending Date',
                'placeholder'=>'Ending Date',
                'dimension' => 2,
                'validators' => array($date_validator),
                'append' => array(
                    'name' => 'endDateButton',
                    'label' => '',
                    'icon' => 'calendar'
                ),
                'attribs' => array(
                    'required' => 'required'
                ),
            )
        );

        // region
        if ($this->regions) {
            $this->addElement(
                'multiselect',
                'region',
                array(
                    'label' => 'Region',
                    'dimension' => 3,
                    'MultiOptions' => $this->regions,
                    'attribs' => array(
                        'class' => 'chosen',
                        'data-placeholder' => 'Filter by Region',
                    ),
                )
            );
        }

        // states
        if ($this->states) {
            $this->addElement(
                'multiselect',
                'state',
                array(
                    'label' => 'State',
                    'dimension' => 3,
                    'MultiOptions' => $this->states,
                    'attribs' => array(
                        'class' => 'chosen',
                        'data-placeholder' => 'Filter by State',
                    ),
                )
            );
        }

        // schoolTypes
        if ($this->schoolTypes) {
            $this->addElement(
                'multiselect',
                'school_type',
                array(
                    'label' => 'School Types',
                    'dimension' => 3,
                    'MultiOptions' => $this->schoolTypes,
                    'attribs' => array(
                        'class' => 'chosen',
                        'data-placeholder' => 'Filter by School Type',
                    ),
                )
            );
        }

        // members
        if ($this->members) {
            $this->addElement(
                'multiselect',
                'member',
                array(
                    'label' => 'Member',
                    'dimension' => 3,
                    'MultiOptions' => $this->members,
                    'attribs' => array(
                        'class' => 'chosen',
                        'data-placeholder' => 'Filter by Member',
                    ),
                )
            );
        }

        $this->addElement(
            'button',
            'submit',
            array(
                'label' => 'Build Report',
                'type' => 'submit',
                'buttonType' => 'primary'
            )
        );

        $this->addDisplayGroup(
            array('submit'),
            'actions',
            array(
                'disableLoadDefaultDecorators' => true,
                'decorators' => array('Actions')
            )
        );
    }

    /**
     * @param mixed $regions
     */
    public function setRegions($regions)
    {
        $this->regions = $regions;
    }

    /**
     * @param array $states
     */
    public function setStates($states)
    {
        $this->states = $states;
    }

    /**
     * @param array $members
     */
    public function setMembers($members)
    {
        $this->members = $members;
    }

    /**
     * @param array $schoolTypes
     */
    public function setSchoolTypes($schoolTypes)
    {
        $this->schoolTypes = $schoolTypes;
    }
}
