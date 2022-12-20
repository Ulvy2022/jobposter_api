<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use LucasDotVin\Soulbscription\Enums\PeriodicityType;
use LucasDotVin\Soulbscription\Models\Feature;

class FeaturesSeeder extends Seeder
{
    public function run()
    {

        $storage = Feature::create([
            'consumable' => false,
            'quota' => true, //limited amount or number that is officially allowed
            'name' => 'trail',
            'postpaid' => 0,
        ]);

        $deployMinutes = Feature::create([
            'consumable' => true,
            'name' => 'silver', //name of feature
            'periodicity_type' => PeriodicityType::Month,
            'postpaid' => 20,
            'periodicity' => 1,
        ]);

        $gold = Feature::create([
            'consumable' => true,
            'name' => 'gold', //name of feature
            'periodicity_type' => PeriodicityType::Month,
            'periodicity' => 1,
            'postpaid' => 35,
        ]);

        $customDomain = Feature::create([
            'consumable' => true,
            'name' => 'diamond',
            'periodicity_type' => PeriodicityType::Month,
            'postpaid' => 50,
        ]);

    }
}
