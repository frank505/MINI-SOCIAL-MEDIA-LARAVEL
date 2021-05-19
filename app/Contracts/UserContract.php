<?php
namespace  App\Contracts;

interface  UserContract
{

    public function getAccountDetails($id);

    public function updateProfilePictureName($filename,$id);

    public function editBioMessage($bioMessage,$id);

    public function changePassword($hashPassword,$id);

    public function editProfileStatus($profileStatus,$id);

    public function allPaginatedUsers($itemsPerPage);

}
