<?php

class Admin_RegionRename extends Twitter_Bootstrap_Form_Horizontal
{

    public function init()
    {
        $this->setName('regionRenameForm');
        $this->setMethod('post');

        // area name
        $this->addElement(
            'text',
            'name',
            array(
                'label' => 'Name',
                'dimension' => 4,
                'required' => true
            )
        );

        // submit button
        $this->addElement(
            'button',
            'submit',
            array(
                'label' => 'Rename Region',
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
}
