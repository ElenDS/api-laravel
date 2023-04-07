<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountriesTableSeeder extends Seeder
{
    public $countries;
    public $continents;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->countries = json_decode(file_get_contents(__DIR__ . '/countries.json'));
        $this->continents = json_decode(file_get_contents(__DIR__ . '/continents.json'));

        foreach ($this->countries as $key => $country) {
            DB::table('countries')->insert([
                'code' => $key,
                'name' => $country,
                'continent' => $this->continents->$key
            ]);
        }

    }
}
