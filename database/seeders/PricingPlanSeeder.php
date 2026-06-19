<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MartialArtsClass;
use App\Models\PricingPlan;

class PricingPlanSeeder extends Seeder
{
    public function run()
    {
        // 1. Add pricing plans for Kids/Youth programs
        $youthCategories = ['Kids', 'Youth'];
        $youthClasses = MartialArtsClass::whereIn('category', $youthCategories)->get();

        foreach ($youthClasses as $class) {
            // Plan 1: 2 Classes per Week
            PricingPlan::updateOrCreate(
                ['martial_arts_class_id' => $class->id, 'name' => '2 Classes per Week'],
                [
                    'price' => 150.00,
                    'interval' => 'monthly',
                    'class_limit_per_week' => 2,
                    'is_tax_inclusive' => false,
                    'is_active' => true,
                    'description' => 'Attend any 2 classes per week.'
                ]
            );

            // Plan 2: Unlimited Classes
            PricingPlan::updateOrCreate(
                ['martial_arts_class_id' => $class->id, 'name' => 'Unlimited Classes (4 days/week)'],
                [
                    'price' => 200.00,
                    'interval' => 'monthly',
                    'class_limit_per_week' => 4, // 4 days a week
                    'is_tax_inclusive' => false,
                    'is_active' => true,
                    'description' => 'Full access to all scheduled classes (up to 4 days a week).'
                ]
            );
        }

        // 2. Add Global Training Options (null class_id)
        PricingPlan::updateOrCreate(
            ['martial_arts_class_id' => null, 'name' => 'Drop-In Class'],
            [
                'price' => 25.00,
                'interval' => 'one-time',
                'class_limit_per_week' => 1,
                'is_tax_inclusive' => false, // $25 + tax
                'is_active' => true,
                'description' => 'Single day drop-in class. No commitment.'
            ]
        );

        PricingPlan::updateOrCreate(
            ['martial_arts_class_id' => null, 'name' => 'Day Pass'],
            [
                'price' => 40.00,
                'interval' => 'one-time',
                'class_limit_per_week' => null,
                'is_tax_inclusive' => true, // $40 tax included
                'is_active' => true,
                'description' => 'Full access to the gym for one day. Tax included.'
            ]
        );

        PricingPlan::updateOrCreate(
            ['martial_arts_class_id' => null, 'name' => 'Weekly Drop-In Pass'],
            [
                'price' => 100.00,
                'interval' => 'weekly',
                'class_limit_per_week' => null,
                'is_tax_inclusive' => false, // $100 + tax
                'is_active' => true,
                'description' => 'Full access to the gym for an entire week.'
            ]
        );
    }
}
