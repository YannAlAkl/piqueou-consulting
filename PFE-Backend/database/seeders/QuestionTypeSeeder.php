<?php

namespace Database\Seeders;

use App\Models\QuestionType;
use Illuminate\Database\Seeder;

class QuestionTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'text'],
            ['name' => 'yes_no'],
            ['name' => 'multiple_choice'],
            ['name' => 'textarea'],
        ];

        foreach ($types as $type) {
            QuestionType::updateOrCreate(
                ['name' => $type['name']],
                ['name' => $type['name']]
            );
        }
    }
}
