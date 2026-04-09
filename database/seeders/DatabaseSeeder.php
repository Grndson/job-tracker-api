<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name'     => 'Test User',
                'password' => Hash::make('password'),
            ]
        );

        $samples = [
            ['company_name' => 'Google',    'job_title' => 'Software Engineer',      'status' => 'applied',   'priority' => 'high',   'location' => 'Remote'],
            ['company_name' => 'Stripe',    'job_title' => 'Backend Engineer',       'status' => 'interview', 'priority' => 'high',   'location' => 'San Francisco'],
            ['company_name' => 'Shopify',   'job_title' => 'Full Stack Developer',   'status' => 'wishlist',  'priority' => 'medium', 'location' => 'Remote'],
            ['company_name' => 'Vercel',    'job_title' => 'Developer Advocate',     'status' => 'rejected',  'priority' => 'medium', 'location' => 'Remote'],
            ['company_name' => 'Notion',    'job_title' => 'Frontend Engineer',      'status' => 'offer',     'priority' => 'high',   'location' => 'New York'],
            ['company_name' => 'Atlassian', 'job_title' => 'Platform Engineer',      'status' => 'applied',   'priority' => 'low',    'location' => 'Austin'],
            ['company_name' => 'Linear',    'job_title' => 'Product Engineer',       'status' => 'wishlist',  'priority' => 'medium', 'location' => 'Remote'],
            ['company_name' => 'Figma',     'job_title' => 'Software Engineer II',   'status' => 'applied',   'priority' => 'high',   'location' => 'San Francisco'],
        ];

        foreach ($samples as $sample) {
            $user->applications()->create(array_merge($sample, [
                'applied_date' => now()->subDays(rand(1, 60))->toDateString(),
                'salary_min'   => rand(80, 120) * 1000,
                'salary_max'   => rand(130, 200) * 1000,
            ]));
        }
    }
}