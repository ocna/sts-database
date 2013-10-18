<?php

class Admin_SchoolFilter extends Twitter_Bootstrap_Form_Inline
{
    protected $regions;
    protected $types;
    public function init()
    {
        $this->setName('schoolFilterForm');
        $this->setMethod('get');
        $this->setAction('/admin/school/index');

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

        // types
        $this->addElement(
            'multiselect',
            'type',
            array(
                'label' => 'Type',
                'dimension' => 2,
                'MultiOptions' => $this->types
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

    public function setTypes($types)
    {
        $this->types = $types;
    }
}
