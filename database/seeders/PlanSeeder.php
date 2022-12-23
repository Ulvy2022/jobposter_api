<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use LucasDotVin\Soulbscription\Enums\PeriodicityType;
use LucasDotVin\Soulbscription\Models\Plan;
use LucasDotVin\Soulbscription\Models\Feature;

class PlanSeeder extends Seeder
{
    public function run()
    {
        $trail = Plan::create([
            'name' => 'trail',
            'periodicity_type' => PeriodicityType::Month,
            'periodicity' => 1,
            'benifits' => 'Post 1 job for 15 days'
        ]);

        $silver = Plan::create([
            'name' => 'silver',
            'periodicity_type' => PeriodicityType::Month,
            'periodicity' => 1,
            'benifits' => 'Post 3 job for 15 days'
        ]);

        $gold = Plan::create([
            'name' => 'gold',
            'periodicity_type' => PeriodicityType::Month,
            'periodicity' => 3,
            'grace_days' => 7,
            'benifits' => 'Post 10 job for 15 days'
        ]);

        $diamond = Plan::create([
            'name' => 'diamond',
            'periodicity_type' => PeriodicityType::Month,
            'periodicity' => 5,
            'grace_days' => 7,
            'benifits' => 'Unlimited Post in 30 days'
        ]);





        // $deployMinutes = Feature::whereName('deploy-minutes')->first();
        // $subdomains = Feature::whereName('subdomains')->first();

        // $silver->features()->attach($deployMinutes, ['charges' => 15]);

        // $gold->features()->attach($deployMinutes, ['charges' => 25]);
        // $gold->features()->attach($subdomains);
    }
}
