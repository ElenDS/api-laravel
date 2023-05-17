<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\CountryRepository;

class CreateUserDataService
{
    public function __construct(protected CountryRepository $countryRepository)
    {
    }

    public function createDataUsers(array $request): array
    {
        $dataUsers = [];
        foreach ($request as $user) {
            $dataUsers[] = [
                'name' => $user['username'],
                'email' => $user['email'],
                'country_id' => $this->countryRepository->findByCountryCode($user['country_code'])
            ];
        }

        return $dataUsers;
    }
}
