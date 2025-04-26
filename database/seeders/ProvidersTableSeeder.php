<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvidersTableSeeder extends Seeder
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
                'title' => 'Alrahuz Data',
                'key' => 'AlrahuzData',
                'service' => 'All',
                'status' => 'Active',
                'created_at' => '2024-02-14 11:44:21',
                'updated_at' => '2024-04-16 19:50:21',
            ],
            [
                'id' => 2,
                'adminID' => 1,
                'title' => 'Easyaccess API',
                'key' => 'EasyAccessAPI',
                'service' => 'Cable',
                'status' => 'Active',
                'created_at' => '2024-02-14 11:44:37',
                'updated_at' => '2024-04-16 19:50:27',
            ],
            [
                'id' => 3,
                'adminID' => 1,
                'title' => 'SMEPlug',
                'key' => 'SMEPlug',
                'service' => 'All',
                'status' => 'Inactive',
                'created_at' => '2024-02-14 11:44:54',
                'updated_at' => '2024-04-16 19:50:13',
            ],
            [
                'id' => 4,
                'adminID' => 1,
                'title' => 'BulkSMSNigeria',
                'key' => 'BulkSMSNigeria',
                'service' => 'BulkSMS',
                'status' => 'Active',
                'created_at' => '2024-02-14 11:45:05',
                'updated_at' => '2024-03-05 19:21:31',
            ],
        ]);
    }
}
