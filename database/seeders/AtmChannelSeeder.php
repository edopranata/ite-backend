<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class AtmChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Excel::import(new \App\Imports\ChannelAtmImport(), public_path('master' . DIRECTORY_SEPARATOR .'atm.xlsx'));
    }
}
