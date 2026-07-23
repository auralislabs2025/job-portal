<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            ['name' => 'United Arab Emirates', 'phone_code' => '971', 'iso2' => 'AE', 'iso3' => 'ARE', 'region' => 'Asia', 'subregion' => 'Western Asia'],
            ['name' => 'India', 'phone_code' => '91', 'iso2' => 'IN', 'iso3' => 'IND', 'region' => 'Asia', 'subregion' => 'Southern Asia'],
            ['name' => 'United States', 'phone_code' => '1', 'iso2' => 'US', 'iso3' => 'USA', 'region' => 'Americas', 'subregion' => 'Northern America'],
            ['name' => 'United Kingdom', 'phone_code' => '44', 'iso2' => 'GB', 'iso3' => 'GBR', 'region' => 'Europe', 'subregion' => 'Northern Europe'],
            ['name' => 'Canada', 'phone_code' => '1', 'iso2' => 'CA', 'iso3' => 'CAN', 'region' => 'Americas', 'subregion' => 'Northern America'],
            ['name' => 'Australia', 'phone_code' => '61', 'iso2' => 'AU', 'iso3' => 'AUS', 'region' => 'Oceania', 'subregion' => 'Australia and New Zealand'],
            ['name' => 'Saudi Arabia', 'phone_code' => '966', 'iso2' => 'SA', 'iso3' => 'SAU', 'region' => 'Asia', 'subregion' => 'Western Asia'],
            ['name' => 'Qatar', 'phone_code' => '974', 'iso2' => 'QA', 'iso3' => 'QAT', 'region' => 'Asia', 'subregion' => 'Western Asia'],
            ['name' => 'Kuwait', 'phone_code' => '965', 'iso2' => 'KW', 'iso3' => 'KWT', 'region' => 'Asia', 'subregion' => 'Western Asia'],
            ['name' => 'Oman', 'phone_code' => '968', 'iso2' => 'OM', 'iso3' => 'OMN', 'region' => 'Asia', 'subregion' => 'Western Asia'],
            ['name' => 'Bahrain', 'phone_code' => '973', 'iso2' => 'BH', 'iso3' => 'BHR', 'region' => 'Asia', 'subregion' => 'Western Asia'],
            ['name' => 'Egypt', 'phone_code' => '20', 'iso2' => 'EG', 'iso3' => 'EGY', 'region' => 'Africa', 'subregion' => 'Northern Africa'],
            ['name' => 'Philippines', 'phone_code' => '63', 'iso2' => 'PH', 'iso3' => 'PHL', 'region' => 'Asia', 'subregion' => 'South-Eastern Asia'],
            ['name' => 'Pakistan', 'phone_code' => '92', 'iso2' => 'PK', 'iso3' => 'PAK', 'region' => 'Asia', 'subregion' => 'Southern Asia'],
            ['name' => 'Bangladesh', 'phone_code' => '880', 'iso2' => 'BD', 'iso3' => 'BGD', 'region' => 'Asia', 'subregion' => 'Southern Asia'],
            ['name' => 'Sri Lanka', 'phone_code' => '94', 'iso2' => 'LK', 'iso3' => 'LKA', 'region' => 'Asia', 'subregion' => 'Southern Asia'],
            ['name' => 'Nepal', 'phone_code' => '977', 'iso2' => 'NP', 'iso3' => 'NPL', 'region' => 'Asia', 'subregion' => 'Southern Asia'],
            ['name' => 'Jordan', 'phone_code' => '962', 'iso2' => 'JO', 'iso3' => 'JOR', 'region' => 'Asia', 'subregion' => 'Western Asia'],
            ['name' => 'Lebanon', 'phone_code' => '961', 'iso2' => 'LB', 'iso3' => 'LBN', 'region' => 'Asia', 'subregion' => 'Western Asia'],
            ['name' => 'South Africa', 'phone_code' => '27', 'iso2' => 'ZA', 'iso3' => 'ZAF', 'region' => 'Africa', 'subregion' => 'Southern Africa'],
            ['name' => 'Nigeria', 'phone_code' => '234', 'iso2' => 'NG', 'iso3' => 'NGA', 'region' => 'Africa', 'subregion' => 'Western Africa'],
            ['name' => 'Kenya', 'phone_code' => '254', 'iso2' => 'KE', 'iso3' => 'KEN', 'region' => 'Africa', 'subregion' => 'Eastern Africa'],
            ['name' => 'Germany', 'phone_code' => '49', 'iso2' => 'DE', 'iso3' => 'DEU', 'region' => 'Europe', 'subregion' => 'Western Europe'],
            ['name' => 'France', 'phone_code' => '33', 'iso2' => 'FR', 'iso3' => 'FRA', 'region' => 'Europe', 'subregion' => 'Western Europe'],
            ['name' => 'Italy', 'phone_code' => '39', 'iso2' => 'IT', 'iso3' => 'ITA', 'region' => 'Europe', 'subregion' => 'Southern Europe'],
            ['name' => 'Spain', 'phone_code' => '34', 'iso2' => 'ES', 'iso3' => 'ESP', 'region' => 'Europe', 'subregion' => 'Southern Europe'],
            ['name' => 'Netherlands', 'phone_code' => '31', 'iso2' => 'NL', 'iso3' => 'NLD', 'region' => 'Europe', 'subregion' => 'Western Europe'],
            ['name' => 'Malaysia', 'phone_code' => '60', 'iso2' => 'MY', 'iso3' => 'MYS', 'region' => 'Asia', 'subregion' => 'South-Eastern Asia'],
            ['name' => 'Singapore', 'phone_code' => '65', 'iso2' => 'SG', 'iso3' => 'SGP', 'region' => 'Asia', 'subregion' => 'South-Eastern Asia'],
            ['name' => 'Turkey', 'phone_code' => '90', 'iso2' => 'TR', 'iso3' => 'TUR', 'region' => 'Asia', 'subregion' => 'Western Asia'],
            ['name' => 'China', 'phone_code' => '86', 'iso2' => 'CN', 'iso3' => 'CHN', 'region' => 'Asia', 'subregion' => 'Eastern Asia'],
        ];

        DB::table('countries')->insert($countries);
    }
}
