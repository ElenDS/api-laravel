<?php

namespace App\Services;

use App\Repositories\CountryRepository;

class CreateUserDataService
{
    public function createDataUser(array $request): array
    {
        $dataUsers = [];
        foreach ($request as $user) {
            $dataUsers[] = [
                'name' => $user['username'],
                'email' => $user['email'],
                'country_id' => (new CountryRepository)->findByCountryCode($user['country_code'])
            ];
        }

        return $dataUsers;
    }
}
