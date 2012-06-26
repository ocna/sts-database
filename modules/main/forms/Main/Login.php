<?php
class Main_Login extends Zend_Form
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
        // Add the comment element
        $this->addElement('password', 'password', array(
            'label' => 'Password:' , 
            'required' => true , 
            'validators' => array(
                
                array(
                    'validator' => 'StringLength' , 
                    'options' => array(
                        0 , 10
                    )
                )
            )
        ));
        $this->addElement('checkbox', 'remember', array(
            'label' => 'Remember Me:'
        ));
        $this->addElement('submit', 'submit', array(
            'ignore' => true , 'label' => 'Login'
        ));
    }
}