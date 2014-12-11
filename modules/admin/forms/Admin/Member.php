<?php

class Admin_Member extends Twitter_Bootstrap_Form_Horizontal {

    const PHONE_REGEX = '/^\d{3}-\d{3}-\d{4}/';

    protected $memberTypes;
    protected $states;
    protected $roles;
    protected $memberStatuses;
    protected $memberActivities;
    protected $diagnosisStages;
    protected $phoneNumberTypes;

    public function init() {
        $this->setName('memberForm');
        $this->setMethod('post');

        // first name
        $this->addElement('text', 'firstName', array(
            'label' => 'First Name',
            'dimension' => 2,
            'required' => true
        ));

        // last name
        $this->addElement('text', 'lastName', array(
            'label' => 'Last Name',
            'dimension' => 2,
            'required' => true
        ));

        // email
        $this->addElement('text', 'systemUserEmail', array(
            'label' => 'Email',
            'dimension' => 3,
            'required' => true,
            'validators' => array(
                new \Zend_Validate_EmailAddress()
            ),
            'description' => 'If this member is added as a system user, this email will also be associated with that account.'
        ));

        // type
        $this->addElement('select', 'memberType', array(
            'label' => 'Type',
            'dimension' => 2,
            'MultiOptions' => $this->memberTypes,
            'required' => true,
            'validators' => array(
                new \Zend_Validate_NotEmpty(\Zend_Validate_NotEmpty::ZERO)
            )
        ));

        // status
        $this->addElement('select', 'memberStatus', array(
            'label' => 'Status',
            'dimension' => 2,
            'MultiOptions' => $this->memberStatuses,
            'required' => true,
            'description' => 'Note that unless a member is marked "Active" they may not be added as a system user.'
        ));

        $this->addElement('checkbox', 'is_volunteer', array(
            'label' => 'Volunteer Contact'
        ));

        // activitites
        $this->addElement('multiCheckbox', 'memberActivity', array(
            'label' => 'Activities',
            'MultiOptions' => $this->memberActivities,
            'required' => false,
            'description' => 'Additional activities for a user. These do not require system access.'
        ));

        // date trained
        $this->addElement('text', 'dateTrained', array(
            'label' => 'Date Trained',
            'dimension' => 2,
            'validators' => array(new Zend_Validate_Date(array('format' => 'MM/dd/yyyy'))),
            'append' => array(
                'name' => 'dateTrainedButton',
                'label' => '',
                'icon' => 'calendar'
            )
        ));

        // notes
        $this->addElement('textarea', 'notes', array(
            'label' => 'Notes',
            'dimension' => 4,
            'rows' => 5
        ));

        // phone numbers
        $this->addElement('text', 'homePhone', array(
            'label' => 'Home Phone',
            'dimension' => 2,
            'validators' => array(new Zend_Validate_Regex(array('pattern' => self::PHONE_REGEX))
                ),
            'description' => 'Use the format ###-###-####',
            'errorMessages' => array('Does not match format: ###-###-####')
            )
        );

        $this->addElement('text', 'cellPhone', array(
            'label' => 'Cell Phone',
            'dimension' => 2,
            'validators' => array(new Zend_Validate_Regex(array('pattern' => self::PHONE_REGEX))),
            'description' => 'Use the format xxx-xxx-xxxx'
            )
        );

        $this->addElement('text', 'workPhone', array(
            'label' => 'Work Phone',
            'dimension' => 2,
            'validators' => array(new Zend_Validate_Regex(array('pattern' => self::PHONE_REGEX))),
            'description' => 'Use the format xxx-xxx-xxxx'
            )
        );

        $this->addDisplayGroup(
            array('homePhone','cellPhone','workPhone'),
            'phoneNumbers',
            array('legend' => 'Phone Numbers')
        );

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
        $this->addDisplayGroup(
            array('addressLineOne', 'addressLineTwo', 'city', 'state', 'zip'),
            'address',
            array('legend' => 'Address')
        );

        // diagnosis
        // date diagnosed
        $this->addElement('text', 'diagnosisDate', array(
            'label' => 'Original Diagnosis Date',
            'dimension' => 2,
            'validators' => array(new Zend_Validate_Date(array('format' => 'MM/dd/yyyy'))),
            'append' => array(
                'name' => 'diagnosisDateButton',
                'label' => '',
                'icon' => 'calendar'
            )
            ));

        // stage
        $this->addElement('select', 'diagnosisStage', array(
            'label' => 'Type',
            'dimension' => 2,
            'MultiOptions' => $this->diagnosisStages,
        ));
        $this->addDisplayGroup(
            array('diagnosisDate', 'diagnosisStage'),
            'diagnosis', array(
                'legend' => 'Diagnosis'
            )
        );

        // role
        $this->addElement('select', 'role', array(
            'label' => 'Role',
            'dimension' => 2,
            'MultiOptions' => $this->roles,
            'required' => true,
            'description' => "If this Member should have access to this system, select a role type to enter other information.",
        ));

        // username
        $this->addElement('text', 'systemUsername', array(
            'label' => 'Username',
            'dimension' => 2,
            'required' => true,
        ));
        $this->addElement('hidden', 'hiddenSystemUsername', array(
            'dimension' => 2,
        ));

        // password
        $this->addElement('password', 'tempPassword', array(
            'label' => 'Password',
            'dimension' => 2,
            'required' => true,
            'description' => 'The password should be between 6 and 12 characters and be letters and numbers of any case. For example: "N0tSecUr3".',
            'validators' => array(
                new \Zend_Validate_Alnum() ,
                new \Zend_Validate_StringLength(array(
                    'min' => 6,
                    'max' => 12
                ))
            )
        ));

        // password confirm
        $this->addElement('password', 'tempPasswordConfirm', array(
            'label' => 'Confirm',
            'dimension' => 2,
            'required' => true,
            'validators' => array(
                new \Zend_Validate_Alnum() ,
                new \Zend_Validate_StringLength(array(
                    'min' => 6,
                    'max' => 12
                ))
            )
        ));
        // Presents For
        $this->addElement('text', 'presentsFor[]', array(
            'label' => 'Presents For Areas',
            'class' => 'presentsFor',
            'isArray' => true,
            'description' => 'Begin typing names to search for and add areas...',
            'validators' => array(
                new \Zend_Validate_NotEmpty(\Zend_Validate_NotEmpty::EMPTY_ARRAY)
            )
        ));
        // Facilitates For
        $this->addElement('text', 'facilitatesFor[]', array(
            'label' => 'Facilitates For Areas',
            'class' => 'facilitatesFor',
            'isArray' => true,
            'description' => 'Begin typing names to search for and add areas...',
            'validators' => array(
                new \Zend_Validate_NotEmpty(\Zend_Validate_NotEmpty::EMPTY_ARRAY)
            )
        ));

        // Coordinates For
        $this->addElement('text', 'coordinatesFor[]', array(
            'label' => 'Coordinates For Regions',
            'class' => 'coordinatesFor',
            'isArray' => true,
            'description' => 'Begin typing names to search for and add regions...',
            'validators' => array(new \Zend_Validate_NotEmpty(\Zend_Validate_NotEmpty::EMPTY_ARRAY))
        ));
        $this->addDisplayGroup(
            array(
                'role',
                'systemUsername',
                'tempPassword',
                'tempPasswordConfirm',
                'presentsFor[]',
                'facilitatesFor[]',
                'coordinatesFor[]'
            ),
            'systemUser',
            array(
                'legend' => 'System User'
            )
        );

        // Saving
        $this->addElement('button', 'submit', array(
            'label' => 'Save Member!',
            'type' => 'submit',
            'buttonType' => Twitter_Bootstrap_Form_Element_Submit::BUTTON_SUCCESS
        ));
        $this->addDisplayGroup(
            array('submit'),
            'actions',
            array(
                'disableLoadDefaultDecorators' => true,
                'decorators' => array(
                    'Actions'
                )
            )
        );
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

    public function setDiagnosisStages($diagnosisStages)
    {
        $this->diagnosisStages = $diagnosisStages;
    }

    public function setPhoneNumberTypes($phoneNumberTypes)
    {
        $this->phoneNumberTypes = $phoneNumberTypes;
    }

    /**
     * @param mixed $memberActivities
     */
    public function setMemberActivities($memberActivities)
    {
        $this->memberActivities = $memberActivities;
    }


}
