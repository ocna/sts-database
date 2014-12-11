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
            ->withSchoolName($presentation->getLocation()->getName())
            ->withSchoolAreaCity($presentation->getLocation()->getArea()->getCity())
            ->withNumberOfParticipants($presentation->getNumberOfParticipants())
            ->withDate($presentation->getDate())
            ->withType($presentation->getType())
            ->withNumberOfFormsReturnedPost($presentation->getNumberOfFormsReturnedPost())
            ->withNumberOfFormsReturnedPre($presentation->getNumberOfFormsReturnedPre());
        if (! is_null($presentation->getSurvey())) {
            $core = \STS\Core::getDefaultInstance();
            $surveyFacade = $core->load('SurveyFacade');
            $survey = $surveyFacade->getSurveyById($presentation->getSurvey()->getId());
            $presentation->setSurvey($survey);
            $builder->withSurveyId($presentation->getSurvey()->getId())
                ->withCorrectBeforePercentage($presentation->getCorrectBeforePercentage())
                ->withCorrectAfterPercentage($presentation->getCorrectAfterPercentage())
                ->withEffectivenessPercentage($presentation->getEffectivenessPercentage());
        }
        if (! is_null($presentation->getLocation())) {
            $builder->withSchoolId($presentation->getLocation()->getId());
        }
        if (! is_null($presentation->getProfessionalGroup())) {
            $builder->withProfessionalGroupName($presentation->getProfessionalGroup()->getName());
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
