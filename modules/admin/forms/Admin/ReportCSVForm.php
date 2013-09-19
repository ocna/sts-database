<?php
class Admin_ReportCSVForm extends Twitter_Bootstrap_Form_Vertical
{
    protected $variables = array(
        'region' => 'Region',
        'state' => "States",
        'schoolType' => 'School Type',
        'member' => 'Member Names',
    );
    public function init()
    {
        $this->setName('reportCSVForm');
        $this->setMethod('get');
        $this->setAction('/admin/report/download');

        $this->addElement('hidden', 'startDate');
        $this->addElement('hidden', 'endDate');
        $this->addElement('hidden', 'region');
        $this->addElement('hidden', 'state');
        $this->addElement('hidden', 'member');
        $this->addElement('hidden', 'schoolType');

        // download options
        $this->addElement(
            'multiCheckbox',
            'vars',
            array(
                'label' => 'Variables',
                'MultiOptions' => $this->variables,
                'required' => true,
            )
        );

        $this->addDisplayGroup(
            array('vars'),
            'varsGroup',
            array('legend' => 'Variables')
        );

        $this->addElement(
            'button',
            'submit',
            array(
                'label' => 'Download Data',
                'type' => 'submit',
                'buttonType' => 'secondary'
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
}