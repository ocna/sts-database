<?php
class Admin_Member extends Twitter_Bootstrap_Form_Horizontal {
    protected $memberTypes;
    protected $states;
    protected $roles;
    protected $memberStatuses;
    public function init() {
        $this->setName('memberForm');
        $this->setMethod('post');
        $this->setAction('/admin/member/new');
        //first name
        $this->addElement('text', 'firstName', array(
            'label' => 'First Name',
            'dimension' => 2,
            'required' => true
        ));
        //last name
        $this->addElement('text', 'lastName', array(
            'label' => 'Last Name',
            'dimension' => 2,
            'required' => true
        ));
        //type
        $this->addElement('select', 'memberType', array(
            'label' => 'Type',
            'dimension' => 2,
            'MultiOptions' => $this->memberTypes,
            'required' => true,
            'validators' => array(
                new \Zend_Validate_NotEmpty(\Zend_Validate_NotEmpty::ZERO)
            )
        ));
        //status
        $this->addElement('select', 'memberStatus', array(
            'label' => 'Status',
            'dimension' => 2,
            'MultiOptions' => $this->memberStatuses,
            'required' => true,
            'description' => 'Note that unless a member is marked "Active" they may not be added as a system user.'
        ));
        //notes
        $this->addElement('textarea', 'notes', array(
            'label' => 'Notes',
            'dimension' => 4,
            'rows' => 5
        ));
        //address
        $this->addElement('text', 'addressLineOne', array(
            'label' => 'Address 1',
            'dimension' => 4,
            'required' => false
        ));
        $this->addElement('text', 'addressLineTwo', array(
            'label' => 'Address 2',
            'dimension' => 4,
            'required' => false
        ));
        $this->addElement('text', 'city', array(
            'label' => 'City',
            'dimension' => 3,
            'required' => false
        ));
        $this->addElement('select', 'state', array(
            'label' => 'State',
            'dimension' => 3,
            'MultiOptions' => $this->states,
            'required' => false,
        ));
        $this->addElement('text', 'zip', array(
            'label' => 'Zip',
            'dimension' => 1,
            'required' => false
        ));
        $this->addDisplayGroup(array(
            'addressLineOne',
            'addressLineTwo',
            'city',
            'state',
            'zip'
        ) , 'address', array(
            'legend' => 'Address'
        ));
        //role
        $this->addElement('select', 'role', array(
            'label' => 'Type',
            'dimension' => 2,
            'MultiOptions' => $this->roles,
            'required' => true,
            'description' => "If this Member should have access to this system, select a role type to enter other information.",
        ));
        //system email
        $this->addElement('text', 'systemUserEmail', array(
            'label' => 'Email',
            'dimension' => 3,
            'required' => true,
            'validators' => array(
                new \Zend_Validate_EmailAddress()
            )
        ));
        //username
        $this->addElement('text', 'systemUsername', array(
            'label' => 'Username',
            'dimension' => 2,
            'required' => true,
            'description' => 'The username and a temporary password will be emailed to the system user\'s email address above upon save.'
        ));
        //Presents For
        $this->addElement('text', 'presentsFor[]', array(
            'label' => 'Presents For Areas',
            'class' => 'presentsFor',
            'isArray' => true,
            'description' => 'Begin typing names to search for and add areas...',
            'validators' => array(
                new \Zend_Validate_NotEmpty(\Zend_Validate_NotEmpty::EMPTY_ARRAY)
            )
        ));
        //Facilitates For
        $this->addElement('text', 'facilitatesFor[]', array(
            'label' => 'Facilitates For Areas',
            'class' => 'facilitatesFor',
            'isArray' => true,
            'description' => 'Begin typing names to search for and add areas...',
            'validators' => array(
                new \Zend_Validate_NotEmpty(\Zend_Validate_NotEmpty::EMPTY_ARRAY)
            )
        ));
        //Coordinates For
        $this->addElement('text', 'coordinatesFor[]', array(
            'label' => 'Coordinates For Regions',
            'class' => 'coordinatesFor',
            'isArray' => true,
            'description' => 'Begin typing names to search for and add regions...',
            'validators' => array(
                new \Zend_Validate_NotEmpty(\Zend_Validate_NotEmpty::EMPTY_ARRAY)
            )
        ));
        $this->addDisplayGroup(array(
            'role',
            'systemUserEmail',
            'systemUsername',
            'systemUserEmail',
            'presentsFor[]',
            'facilitatesFor[]',
            'coordinatesFor[]'
        ) , 'systemUser', array(
            'legend' => 'System User'
        ));
        //Saving
        $this->addElement('button', 'submit', array(
            'label' => 'Save New Member!',
            'type' => 'submit',
            'buttonType' => Twitter_Bootstrap_Form_Element_Submit::BUTTON_SUCCESS
        ));
        $this->addDisplayGroup(array(
            'submit'
        ) , 'actions', array(
            'disableLoadDefaultDecorators' => true,
            'decorators' => array(
                'Actions'
            )
        ));
    }
    public function setStates($states) {
        $this->states = $states;
    }
    public function setRoles($roles) {
        
        foreach ($roles as & $role) {
            
            switch ($role) {
                case 'admin':
                    $role = 'Site Administrator';
                    break;

                case 'coordinator':
                    $role = 'Regional Coordinator';
                    break;

                case 'facilitator':
                    $role = 'Area Facilitator';
                    break;
            }
        }
        $this->roles = $roles;
    }
    public function setMemberTypes($memberTypes) {
        $this->memberTypes = $memberTypes;
    }
    public function setMemberStatuses($memberStatuses) {
        $this->memberStatuses = $memberStatuses;
    }
}
