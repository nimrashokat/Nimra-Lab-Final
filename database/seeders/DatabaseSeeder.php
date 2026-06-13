<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@shestitch.com'],
            ['name' => 'SheStitch Admin', 'password' => Hash::make('password123'), 'role' => 'admin']
        );

        $categories = [
            'Neck Designs', 'Sleeve Designs', 'Daman Designs',
            'Trouser Designs', 'Suit Designs', 'Frock Designs',
            'Lehenga Designs', 'Maxi Designs', 'Bridal Designs'
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => Str::slug($cat)],
                ['name' => $cat]
            );
        }

        $reviews = [
            'Excellent stitching', 'Perfect fitting', 'Beautiful designs',
            'Fast delivery', 'Affordable prices', 'Highly recommended'
        ];

        foreach ($reviews as $review) {
            Review::updateOrCreate(
                ['review_text' => $review],
                [
                    'name' => fake()->name(),
                    'rating' => 5,
                    'image' => 'images/reviews/default.jpg'
                ]
            );
        }
    }
}