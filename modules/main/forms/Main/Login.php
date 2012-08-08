<?php
class Main_Login extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');
        $this->setAction('/session/login');
        // Add an email element
        $this->addElement('text', 'userName', array(
            'label' => 'User Name:' , 
            'required' => true , 
            'filters' => array(
                'StringTrim'
            ) , 
         
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
//         $this->addElement('checkbox', 'remember', array(
//             'label' => 'Remember Me:'
//         ));
        $this->addElement('submit', 'submit', array(
            'ignore' => true , 'label' => 'Login'
        ));
    }
}