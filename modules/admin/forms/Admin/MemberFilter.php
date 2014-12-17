<?php

class Admin_MemberFilter extends Twitter_Bootstrap_Form_Inline
{
    protected $roles;
    protected $regions;
    protected $presentsFor;
    protected $memberStatuses;
    public function init()
    {
        $this->setName('memberFilterForm');
        $this->setMethod('get');
        $this->setAction('/admin/member/index');
        //role
        $this->addElement('multiselect', 'role', array(
            'label' => 'Role',
            'dimension' => 2,
            'MultiOptions' => $this->roles
        ));

        //status
        $this->addElement('multiselect', 'status', array(
            'label' => 'Status',
            'dimension' => 2,
            'MultiOptions' => $this->memberStatuses
        ));

        //region
        $this->addElement('multiselect', 'region', array(
            'label' => 'Region',
            'dimension' => 2,
            'MultiOptions' => $this->regions
        ));

       // area
        $this->addElement('multiselect', 'presents_for', array(
            'label' => 'Area',
            'dimension' => 2,
            'multiOptions' => $this->presentsFor
        ));

        $this->addElement('multiselect', 'is_volunteer', array(
            'label' => 'Volunteer',
            'dimension' => 2,
            'MultiOptions' => array(
                '' => '',
                1   => 'Volunteer',
                0   => 'Not a Volunteer'
            )
        ));

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

    public function setRoles($roles)
    {
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
    public function setRegions($regions)
    {
        $this->regions = $regions;
    }

    public function setPresentsFor($presentsFor)
    {
        $this->presentsFor = $presentsFor;
    }

    public function setMemberStatuses($memberStatuses)
    {
        $this->memberStatuses = $memberStatuses;
    }
}
