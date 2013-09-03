<?php
namespace STS\Core\Api;
interface MemberFacade
{
    public function getMemberById($id);

    public function getAllMembers();

    public function getMembersMatching($criteria);

    public function searchForMembersByNameWithSpec($searchString, $spec);

    public function getMemberByMemberAreaSpecForId($id);

    public function getMemberSchoolSpecForId($id);

    /**
     * saveMember
     *
     * @param $firstName
     * @param $lastName
     * @param $type
     * @param $status
     * @param $activities
     * @param $notes
     * @param $presentsFor
     * @param $facilitatesFor
     * @param $coordinatesFor
     * @param $userId
     * @param $addressLineOne
     * @param $addressLineTwo
     * @param $city
     * @param $state
     * @param $zip
     * @param $email
     * @param $dateTrained
     * @param $diagnosisInfo
     * @param $phoneNumbers
     * @return mixed
     */
    public function saveMember($firstName, $lastName, $type, $status, $activities, $notes,
        $presentsFor, $facilitatesFor, $coordinatesFor, $userId, 
        $addressLineOne, $addressLineTwo, $city, $state,
                    $zip, $email, $dateTrained, $diagnosisInfo, $phoneNumbers);

    /**
     * @param $id
     * @param $firstName
     * @param $lastName
     * @param $type
     * @param $status
     * @param $activities
     * @param $notes
     * @param $presentsFor
     * @param $facilitatesFor
     * @param $coordinatesFor
     * @param $userId
     * @param $addressLineOne
     * @param $addressLineTwo
     * @param $city
     * @param $state
     * @param $zip
     * @param $email
     * @param $dateTrained
     * @param $diagnosisInfo
     * @param $phoneNumbers
     * @return \Sts\Core\Member\MemberDto
     */
    public function updateMember($id, $firstName, $lastName, $type, $status, $activities, $notes,
        $presentsFor, $facilitatesFor, $coordinatesFor, $userId, 
        $addressLineOne, $addressLineTwo, $city, $state,
                    $zip, $email, $dateTrained, $diagnosisInfo, $phoneNumbers);

    /**
     * deleteMember
     *
     * @param $id
     * @return mixed
     */
    public function deleteMember($id);
}
