<?php

namespace Database\Seeders;

use App\Models\Review;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $review = new Review();
        $review->product_id = "1";
        $review->customer_id = "NOZAMI";
        $review->rating = 4;
        $review->comment = "GOOD";
        $review->save();

        $review2 = new Review();
        $review2->product_id = "2";
        $review2->customer_id = "NOZAMI";
        $review2->rating = 5;
        $review2->comment = "cakep";
        $review2->save();
    }
}
