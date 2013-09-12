<?php

class Admin_ReportBasicForm extends Twitter_Bootstrap_Form_Vertical
{
    protected $regions = array();

    public function __construct($opts = null)
    {
        parent::__construct($opts);

        if (isset($opts['regions'])) {
            $this->setRegions($opts['regions']);
        }
    }

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
                    'icon' => 'calendar'
                )
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
               )
            )
        );

        // region
        $this->addElement(
            'multiselect',
            'region',
            array(
                'label' => 'Region',
                'dimension' => 2,
                'MultiOptions' => $this->regions
            )
        );

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
}
