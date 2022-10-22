<?php

namespace Database\Seeders;

use App\Models\Advertiser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class AdvertiserSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(class_exists(\App\Models\Advertiser::class)){

            \App\Models\Advertiser::factory()->count(5)->create();

        }
        //
    }
}
