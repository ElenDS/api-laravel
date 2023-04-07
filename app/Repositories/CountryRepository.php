<?php

namespace App\Repositories;

use App\Models\Country;

class CountryRepository
{
    public function findByCountryCode($code){
        return Country::where(['code' => $code])->first()->id;
    }
}
