<?php

namespace Database\Seeders;

use App\Models\SearchAnalytics;
use Illuminate\Database\Seeder;

class SearchAnalyticsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $searchTerms = [
            'Frontend Developer',
            'Backend Developer',
            'Full Stack Developer',
            'React Developer',
            'Laravel Developer',
            'UI/UX Designer',
            'Product Manager',
            'Data Scientist',
            'DevOps Engineer',
            'Mobile Developer',
            'Python Developer',
            'JavaScript Developer',
            'Vue.js Developer',
            'Node.js Developer',
            'PHP Developer',
            'React Native',
            'Flutter Developer',
            'Machine Learning Engineer',
            'Software Engineer',
            'Senior Developer',
            'Junior Developer',
            'Remote Developer',
            'Contract Developer',
            'Freelance Developer',
            'Startup Developer',
        ];

        $companies = [
            'Google',
            'Facebook',
            'Amazon',
            'Microsoft',
            'Apple',
            'Netflix',
            'Spotify',
            'Uber',
            'Airbnb',
            'Tesla',
            'SpaceX',
            'Stripe',
            'Shopify',
            'GitHub',
            'Twitter',
        ];

        $ipAddresses = [
            '192.168.1.1',
            '192.168.1.2',
            '192.168.1.3',
            '10.0.0.1',
            '10.0.0.2',
            '172.16.0.1',
            '172.16.0.2',
        ];

        // Generate 1000 sample search records
        for ($i = 0; $i < 1000; $i++) {
            $searchTerm = $searchTerms[array_rand($searchTerms)];
            $isCompanySearch = rand(0, 10) > 7; // 30% chance of company search

            if ($isCompanySearch) {
                $searchTerm = $companies[array_rand($companies)];
            }

            SearchAnalytics::create([
                'query' => $searchTerm,
                'user_id' => rand(0, 10) > 7 ? rand(1, 50) : null, // 30% authenticated users
                'ip_address' => $ipAddresses[array_rand($ipAddresses)],
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'results_count' => rand(0, 200),
                'filters_applied' => rand(0, 10) > 6 ? [
                    'remote' => rand(0, 1),
                    'country' => ['US', 'CA', 'UK', 'DE', 'FR'][rand(0, 4)],
                    'category' => rand(1, 10),
                ] : [],
                'session_id' => 'sess_'.rand(100000, 999999),
                'searched_at' => now()->subDays(rand(0, 30))->subHours(rand(0, 23)),
            ]);
        }

        $this->command->info('Created 1000 sample search analytics records');
    }
}
