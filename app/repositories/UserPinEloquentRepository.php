<?php

namespace App\repositories;

use App\Models\UserPin;

class UserPinEloquentRepository implements UserPinRepository
{

    public function saveUserPinData(int $userId, array $data)
    {
        return UserPin::updateOrCreate(['user_id' => $userId], $data);
    }

    public function getUserPinData(int $userId)
    {
        $result = UserPin::whereUserId($userId)->first();
        return $result?->toArray();
    }
}
