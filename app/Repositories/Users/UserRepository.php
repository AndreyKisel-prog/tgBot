<?php

namespace App\Repositories\Users;

use App\Models\User;

class UserRepository
{
    public function updateUserLocation($user, $data)
    {
        $user->update([
            'last_latitude' => $data['latitude'],
            'last_longitude' => $data['longitude']
        ]);
    }

    public function getUser($nickName)
    {
        return User::firstOrCreate(['user_name' => $nickName]);
    }

    public function setLastSearchWord($user, $searchWord)
    {
        return $user->update(['last_search_word' => $searchWord]);
    }
}
