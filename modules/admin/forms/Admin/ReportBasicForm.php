<?php

class Admin_ReportBasicForm extends Twitter_Bootstrap_Form_Vertical
{
    public function init()
    {
        $this->setName('reportBasicForm');
        $this->setMethod('get');
        $this->setAction('/admin/report/presentation');
        $this->addElement(
            'text',
            'startDate',
            array('label' => 'Starting Date', 'placeholder'=>'Starting Date', 'dimension' => 2, 'validators' => array(
                new Zend_Validate_Date(array('format' => 'MM/dd/yyyy'))
                ),
            'append' => array(
                'name' => 'startDateButton', 'label' => '', 'icon' => 'calendar'
            )
        )
        );

        $this->addElement(
            'text',
            'endDate',
            array('label' => 'Ending Date', 'placeholder'=>'Ending Date', 'dimension' => 2, 'validators' => array(
                new Zend_Validate_Date(array('format' => 'MM/dd/yyyy'))
                ),
            'append' => array(
                'name' => 'endDateButton', 'label' => '', 'icon' => 'calendar'
            )
        )
        );

        $this->addElement('button',
            'submit',
            array(
            'label' => 'Build Report',
            'type' => 'submit',
            'buttonType' => 'primary'
        ));

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
