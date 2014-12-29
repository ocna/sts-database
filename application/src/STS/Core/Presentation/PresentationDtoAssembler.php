<?php
namespace STS\Core\Presentation;

use STS\Domain\Presentation;
use STS\Domain\Member;

class PresentationDtoAssembler
{
    /**
     * @param Presentation $presentation
     *
     * @return PresentationDto
     */
    public static function toDto($presentation)
    {
        $builder = new PresentationDtoBuilder();
        $builder->withId($presentation->getId())
            ->withLocationName($presentation->getLocation()->getName())
            ->withLocationAreaCity($presentation->getLocation()->getArea()->getCity())
            ->withLocationClass(get_class($presentation->getLocation()))
            ->withNumberOfParticipants($presentation->getNumberOfParticipants())
            ->withDate($presentation->getDate())
            ->withType($presentation->getType())
            ->withNumberOfFormsReturnedPost($presentation->getNumberOfFormsReturnedPost())
            ->withNumberOfFormsReturnedPre($presentation->getNumberOfFormsReturnedPre());
        if (! is_null($presentation->getSurvey())) {
            $builder->withSurveyId($presentation->getSurvey()->getId())
                ->withCorrectBeforePercentage($presentation->getCorrectBeforePercentage())
                ->withCorrectAfterPercentage($presentation->getCorrectAfterPercentage())
                ->withEffectivenessPercentage($presentation->getEffectivenessPercentage());
        }
        if (! is_null($presentation->getLocation())) {
            $builder->withSchoolId($presentation->getLocation()->getId());
        }
        $members = $presentation->getMembers();
        $membersArray = array();
        /** @var Member $member */
        foreach ($members as $member) {
            $membersArray[$member->getId()] = array(
                'fullname'=> $member->getFullName(),
                'status' => $member->getStatus()
                );
        }
        $builder->withMembersArray($membersArray);
        $builder->withNotes($presentation->getNotes());
        return $builder->build();
    }
}
