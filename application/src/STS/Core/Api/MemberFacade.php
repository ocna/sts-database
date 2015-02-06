<?php
namespace STS\Core\Api;

interface MemberFacade
{
    /**
     * @param $id
     * @return \STS\Core\Member\MemberDto
     */
    public function getMemberById($id);

    public function getAllMembers();

    public function getMembersMatching($criteria);

    public function searchForMembersByNameWithSpec($searchString, $spec);

    public function getMemberByMemberAreaSpecForId($id);

    public function getMemberLocationSpecForId($id);

    /**
     * saveMember
     *
     * @param $firstName
     * @param $lastName
     * @param $type
     * @param $status
     * @param $is_volunteer
     * @param $activities
     * @param $notes
     * @param $presentsFor
     * @param $facilitatesFor
     * @param $coordinatesFor
     * @param $userId
     * @param $address
     * @param $email
     * @param $dateTrained
     * @param $diagnosisInfo
     * @param $phoneNumbers
     * @return mixed
     */
    public function saveMember(
        $firstName,
        $lastName,
        $type,
        $status,
        $is_volunteer,
        $activities,
        $notes,
        $presentsFor,
        $facilitatesFor,
        $coordinatesFor,
        $userId,
        $address,
        $email,
        $dateTrained,
        $diagnosisInfo,
        $phoneNumbers
    );

    /**
     * @param $id
     * @param $firstName
     * @param $lastName
     * @param $type
     * @param $status
     * @param $is_volunteer
     * @param $activities
     * @param $notes
     * @param $presentsFor
     * @param $facilitatesFor
     * @param $coordinatesFor
     * @param $userId
     * @param $address
     * @param $email
     * @param $dateTrained
     * @param $diagnosisInfo
     * @param $phoneNumbers
     * @return \Sts\Core\Member\MemberDto
     */
    public function updateMember(
        $id,
        $firstName,
        $lastName,
        $type,
        $status,
        $is_volunteer,
        $activities,
        $notes,
        $presentsFor,
        $facilitatesFor,
        $coordinatesFor,
        $userId,
        $address,
        $email,
        $dateTrained,
        $diagnosisInfo,
        $phoneNumbers
    );

    /**
     * deleteMember
     *
     * @param $id
     * @return mixed
     */
    public function deleteMember($id);
}
