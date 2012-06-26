<?php
class Main_ResetPassword extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');
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
        $this->addElement('password', 'password2', array(
            'label' => 'Confirm:' , 
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
        $this->addElement('submit', 'submit', array(
            'ignore' => true , 'label' => 'Reset'
        ));
    }
}

