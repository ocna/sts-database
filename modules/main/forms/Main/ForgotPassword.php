<?php

class Main_ForgotPassword extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');
        // Add an email element
        $this->addElement('text', 'email', array(
            'label' => 'Email address:' , 
            'required' => true , 
            'filters' => array(
                'StringTrim'
            ) , 
            'validators' => array(
                'EmailAddress'
            )
        ));
        $this->addElement('submit', 'submit', array(
            'ignore' => true , 'label' => 'Submit'
        ));
    }


}

