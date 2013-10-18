<?php
/**
 * Class Admin_ReportEffectivenessForm
 */
class Admin_ReportEffectivenessForm extends Twitter_Bootstrap_Form_Vertical
{
    protected $regions = array();
    protected $areas  = array();
    protected $presentationTypes = array();
    protected $schools = array();

    public function init()
    {
        $date_validator = new Zend_Validate_Date(array('format' => 'MM/dd/yyyy'));

        $this->setName('reportEffectivenessForm');
        $this->setMethod('get');
        $this->setAction('/admin/report/effectiveness');
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

        // areas
        if ($this->areas) {
            $this->addElement(
                'multiselect',
                'area',
                array(
                    'label' => 'Area',
                    'dimension' => 3,
                    'MultiOptions' => $this->areas,
                    'attribs' => array(
                        'class' => 'chosen',
                        'data-placeholder' => 'Filter by Area',
                    ),
                )
            );
        }

        // presentationTypes
        if ($this->presentationTypes) {
            $this->addElement(
                'multiselect',
                'presentation_type',
                array(
                    'label' => 'Presentation Type',
                    'dimension' => 3,
                    'MultiOptions' => $this->presentationTypes,
                    'attribs' => array(
                        'class' => 'chosen',
                        'data-placeholder' => 'Filter by Presentation Type',
                    ),
                )
            );
        }

        // schools
        if ($this->schools) {
            $this->addElement(
                'multiselect',
                'school',
                array(
                    'label' => 'School',
                    'dimension' => 3,
                    'MultiOptions' => $this->schools,
                    'attribs' => array(
                        'class' => 'chosen',
                        'data-placeholder' => 'Filter by School',
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
     * @param array $areas
     */
    public function setAreas($areas)
    {
        $this->areas = $areas;
    }

    /**
     * @param array $presentation_types
     */
    public function setPresentationTypes($presentation_types)
    {
        $this->presentationTypes = $presentation_types;
    }

    /**
     * @param array $schools
     */
    public function setSchools($schools)
    {
        $this->schools = $schools;
    }

}