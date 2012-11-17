<?php

namespace STS\Core\Presentation;

class PresentationDtoAssembler
{
    public static function toDto($presentation)
    {
        $builder = new PresentationDtoBuilder();
        $builder->withId($presentation->getId());
        $builder->withSchoolName($presentation->getLocation()->getName());
        $builder->withSchoolAreaCity($presentation->getLocation()->getArea()->getCity());
        $builder->withNumberOfParticipants($presentation->getNumberOfParticipants());
        $builder->withDate($presentation->getDate());
        $builder->withType($presentation->getType());
        $builder->withNumberOfFormsReturnedPost($presentation->getNumberOfFormsReturnedPost());
        $builder->withNumberOfFormsReturnedPre($presentation->getNumberOfFormsReturnedPre());
        if (! is_null($presentation->getSurvey())) {
            $builder->withSurveyId($presentation->getSurvey()->getId());
        }
        if (! is_null($presentation->getLocation())) {
            $builder->withSchoolId($presentation->getLocation()->getId());
        }
        $members = $presentation->getMembers();
        $membersArray = array();
        foreach ($members as $member) {
            $membersArray[$member->getId()] = array(
                'fullname'=> $member->getStatus(),
                'status' => $member->getFullName()
                );
        }
        $builder->withMembersArray($membersArray);
        $builder->withNotes($presentation->getNotes());
        return $builder->build();
    }
}
