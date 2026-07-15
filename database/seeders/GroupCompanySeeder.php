<?php

namespace Database\Seeders;

use App\Models\GroupCompany;
use Illuminate\Database\Seeder;

class GroupCompanySeeder extends Seeder
{
    public function run(): void
    {
        $companies = [
            ['name' => 'Transport Division', 'code' => 'TRN', 'email' => 'transport@abn.ae', 'phone' => '+971 4 123 4567', 'address' => 'Sheikh Zayed Road', 'city' => 'Dubai', 'country' => 'UAE', 'description' => 'Core logistics and fleet management division.', 'is_active' => true],
            ['name' => 'Petroleum Division', 'code' => 'PET', 'email' => 'petroleum@abn.ae', 'phone' => '+971 4 234 5678', 'address' => 'Al Ahmadi', 'city' => 'Kuwait City', 'country' => 'Kuwait', 'description' => 'Exploration and trading of petroleum products.', 'is_active' => true],
            ['name' => 'Equipment Division', 'code' => 'EQP', 'email' => 'equipment@abn.ae', 'phone' => '+971 6 345 6789', 'address' => 'Industrial Area 4', 'city' => 'Sharjah', 'country' => 'UAE', 'description' => 'Heavy equipment sales, rental and servicing.', 'is_active' => true],
            ['name' => 'Steel & Engineering', 'code' => 'STL', 'email' => 'steel@abn.ae', 'phone' => '+971 4 456 7890', 'address' => 'Jebel Ali Industrial Zone', 'city' => 'Dubai', 'country' => 'UAE', 'description' => 'Structural steel manufacturing and engineering services.', 'is_active' => true],
            ['name' => 'Steel Fabrication', 'code' => 'SFB', 'email' => 'fabrication@abn.ae', 'phone' => '+971 2 567 8901', 'address' => 'Musaffah Industrial Area', 'city' => 'Abu Dhabi', 'country' => 'UAE', 'description' => 'Custom steel fabrication and welding solutions.', 'is_active' => true],
            ['name' => "Bhavan's Public School", 'code' => 'BPS', 'email' => 'school@abn.ae', 'phone' => '+971 4 678 9012', 'address' => 'Al Qusais', 'city' => 'Dubai', 'country' => 'UAE', 'description' => 'K-12 educational institution serving the community.', 'is_active' => true],
            ['name' => 'Property Developers', 'code' => 'PRP', 'email' => 'property@abn.ae', 'phone' => '+971 4 789 0123', 'address' => 'Business Bay', 'city' => 'Dubai', 'country' => 'UAE', 'description' => 'Residential and commercial real estate development.', 'is_active' => true],
            ['name' => 'International General Trading', 'code' => 'IGT', 'email' => 'trading@abn.ae', 'phone' => '+971 4 890 1234', 'address' => 'Port Saeed', 'city' => 'Dubai', 'country' => 'UAE', 'description' => 'Import, export and general trading across the region.', 'is_active' => true],
            ['name' => 'MEDCORP Trading', 'code' => 'MED', 'email' => 'medcorp@abn.ae', 'phone' => '+971 4 901 2345', 'address' => 'Dubai Healthcare City', 'city' => 'Dubai', 'country' => 'UAE', 'description' => 'Distribution of pharmaceuticals and medical supplies.', 'is_active' => true],
            ['name' => 'International Transport', 'code' => 'INT', 'email' => 'int-transport@abn.ae', 'phone' => '+971 4 012 3456', 'address' => 'Jebel Ali Free Zone', 'city' => 'Dubai', 'country' => 'UAE', 'description' => 'Cross-border freight and shipping logistics.', 'is_active' => true],
        ];

        foreach ($companies as $company) {
            GroupCompany::create($company);
        }
    }
}
