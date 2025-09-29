<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Chapter;

class ChapterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $chapters = [
            [
                'name' => 'Sorsogon City Chapter',
                'location' => 'Sorsogon City, Sorsogon',
                'description' => 'Main chapter serving the capital city of Sorsogon province. This chapter coordinates with the provincial government and serves as the central hub for church activities in the region.',
                'status' => 'active',
            ],
            [
                'name' => 'Bulan Chapter',
                'location' => 'Bulan, Sorsogon',
                'description' => 'Chapter serving the coastal municipality of Bulan, a major commercial hub in southwestern Sorsogon. Focuses on serving the fishing and business communities.',
                'status' => 'active',
            ],
            [
                'name' => 'Gubat Chapter',
                'location' => 'Gubat, Sorsogon',
                'description' => 'Chapter serving the municipality of Gubat, known for its beautiful beaches and surfing spots. Engages with the tourism and local community.',
                'status' => 'active',
            ],
            [
                'name' => 'Bulusan Chapter',
                'location' => 'Bulusan, Sorsogon',
                'description' => 'Chapter serving the municipality of Bulusan, home to the famous Bulusan Volcano and Bulusan Lake. Focuses on eco-tourism and environmental stewardship.',
                'status' => 'active',
            ],
            [
                'name' => 'Casiguran Chapter',
                'location' => 'Casiguran, Sorsogon',
                'description' => 'Chapter serving the quiet town of Casiguran, known for its agricultural and fishing activities. Provides spiritual guidance to rural communities.',
                'status' => 'active',
            ],
            [
                'name' => 'Pilar Chapter',
                'location' => 'Pilar, Sorsogon',
                'description' => 'Chapter serving the municipality of Pilar, famous for whale shark (butanding) interaction tours in Donsol. Engages with tourism and conservation efforts.',
                'status' => 'active',
            ],
        ];

        foreach ($chapters as $chapter) {
            Chapter::create($chapter);
        }
    }
} 