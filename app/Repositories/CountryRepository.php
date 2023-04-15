<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Country;

class CountryRepository
{
    public function findByCountryCode(string $code): int
    {
        return Country::where(['code' => $code])->first()->id;
    }
}
