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
        return $builder->build();
    }
}
