<?php

namespace App\repositories;

interface UserPinRepository
{
    public function saveUserPinData(int $userId, array $data);

    public function getUserPinData(int $userId);
}
