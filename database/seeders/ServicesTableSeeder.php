<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('services')->insert([
            [
                'id' => 1,
                'adminID' => 1,
                'providerID' => 3,
                'title' => 'Airtime',
                'status' => 'Active',
                'created_at' => '2024-02-14 11:44:21',
                'updated_at' => '2024-04-16 19:50:21',
            ],
            [
                'id' => 2,
                'adminID' => 1,
                'providerID' => 3,
                'title' => 'Data',
                'status' => 'Active',
                'created_at' => '2024-02-14 11:44:37',
                'updated_at' => '2024-04-16 19:50:27',
            ],
            [
                'id' => 3,
                'adminID' => 1,
                'providerID' => 2,
                'title' => 'Cable',
                'status' => 'Inactive',
                'created_at' => '2024-02-14 11:44:54',
                'updated_at' => '2024-04-16 19:50:13',
            ],
            [
                'id' => 4,
                'adminID' => 1,
                'providerID' => 4,
                'title' => 'Bulk SMS',
                'status' => 'Active',
                'created_at' => '2024-02-14 11:45:05',
                'updated_at' => '2024-03-05 19:21:31',
            ],
            [
                'id' => 5,
                'adminID' => 1,
                'providerID' => 2,
                'title' => 'Electricity',
                'status' => 'Active',
                'created_at' => '2024-02-14 11:45:05',
                'updated_at' => '2024-03-05 19:21:31',
            ],
            [
                'id' => 6,
                'adminID' => 1,
                'providerID' => 2,
                'title' => 'Education',
                'status' => 'Active',
                'created_at' => '2024-02-14 11:45:05',
                'updated_at' => '2024-03-05 19:21:31',
            ],

        ]);
    }
}
