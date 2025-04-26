<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BillersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('billers')->insert([
            [
                'id' => 1,
                'adminID' => 1,
                'title' => 'MTN',
                'status' => 'Active',
                'created_at' => '2024-02-14 11:41:22',
                'updated_at' => '2024-02-14 11:41:22',
                'variation' => 1,
            ],
            [
                'id' => 2,
                'adminID' => 1,
                'title' => 'GLO',
                'status' => 'Active',
                'created_at' => '2024-02-14 11:42:20',
                'updated_at' => '2024-02-14 11:42:20',
                'variation' => 2,
            ],
            [
                'id' => 3,
                'adminID' => 1,
                'title' => 'AIRTEL',
                'status' => 'Active',
                'created_at' => '2024-02-14 11:42:36',
                'updated_at' => '2024-02-14 11:42:36',
                'variation' => 4,
            ],
            [
                'id' => 4,
                'adminID' => 1,
                'title' => '9MOBILE',
                'status' => 'Active',
                'created_at' => '2024-02-14 11:42:52',
                'updated_at' => '2024-02-14 11:42:52',
                'variation' => 3,
            ],
            [
                'id' => 5,
                'adminID' => 1,
                'title' => 'DSTV',
                'status' => 'Inactive',
                'created_at' => '2024-03-05 19:19:03',
                'updated_at' => '2024-03-09 20:39:16',
                'variation' => 0,
            ],
        ]);
    }
}
