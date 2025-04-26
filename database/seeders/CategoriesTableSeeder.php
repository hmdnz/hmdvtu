<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('categories')->insert([
            [
                'id' => 1,
                'adminID' => 1,
                'service' => 'Airtime',
                'title' => 'VTU',
                'mtn' => 'Active',
                'airtel' => 'Active',
                'glo' => 'Active',
                'mobile' => 'Active',
                'status' => 'Active',
                'created_at' => '2024-02-14 13:11:35',
                'updated_at' => '2024-02-14 13:11:35',
            ],
            [
                'id' => 2,
                'adminID' => 1,
                'service' => 'Data',
                'title' => 'SME',
                'mtn' => 'Active',
                'airtel' => 'Active',
                'glo' => 'Active',
                'mobile' => 'Active',
                'status' => 'Active',
                'created_at' => '2024-02-14 16:55:48',
                'updated_at' => '2024-02-14 16:55:48',
            ],
            [
                'id' => 3,
                'adminID' => 1,
                'service' => 'Data',
                'title' => 'CG',
                'mtn' => 'Active',
                'airtel' => 'Active',
                'glo' => 'Active',
                'mobile' => 'Active',
                'status' => 'Active',
                'created_at' => '2024-02-14 13:11:35',
                'updated_at' => '2024-02-14 13:11:35',
            ],
            [
                'id' => 4,
                'adminID' => 1,
                'service' => 'Data',
                'title' => 'GIFTING',
                'mtn' => 'Active',
                'airtel' => 'Active',
                'glo' => 'Active',
                'mobile' => 'Active',
                'status' => 'Active',
                'created_at' => '2024-02-14 16:55:48',
                'updated_at' => '2024-02-14 16:55:48',
            ],
            [
                'id' => 5,
                'adminID' => 1,
                'service' => 'Data',
                'title' => 'CG_LITE',
                'mtn' => 'Inactive',
                'airtel' => 'Inactive',
                'glo' => 'Inactive',
                'mobile' => 'Inactive',
                'status' => 'Inactive',
                'created_at' => '2024-02-14 13:11:35',
                'updated_at' => '2024-04-15 19:37:11',
            ],
            [
                'id' => 6,
                'adminID' => 1,
                'service' => 'Data',
                'title' => 'DIRECT',
                'mtn' => 'Active',
                'airtel' => 'Active',
                'glo' => 'Active',
                'mobile' => 'Active',
                'status' => 'Active',
                'created_at' => '2024-02-14 13:11:35',
                'updated_at' => '2024-02-14 13:11:35',
            ],
        ]);
    }
}
