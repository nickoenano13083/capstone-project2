<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Resource;
use App\Models\User;

class ResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first admin user or create one
        $adminUser = User::where('role', 'Admin')->first() ?? User::first();

        $resources = [
            [
                'title' => 'Sunday Service Guidelines',
                'description' => 'Comprehensive guidelines for conducting Sunday services including order of service, responsibilities, and best practices.',
                'type' => 'document',
                'category' => 'administration',
                'url' => 'https://docs.google.com/document/d/example1',
                'is_public' => true,
                'uploaded_by' => $adminUser->id,
            ],
            [
                'title' => 'Bible Study: Book of John',
                'description' => 'Complete study guide for the Book of John with discussion questions, key themes, and application points.',
                'type' => 'pdf',
                'category' => 'bible-study',
                'url' => 'https://drive.google.com/file/d/example2',
                'is_public' => true,
                'uploaded_by' => $adminUser->id,
            ],
            [
                'title' => 'Worship Song Collection 2024',
                'description' => 'Updated collection of worship songs with lyrics, chords, and sheet music for the worship team.',
                'type' => 'document',
                'category' => 'worship',
                'url' => 'https://drive.google.com/folder/d/example3',
                'is_public' => true,
                'uploaded_by' => $adminUser->id,
            ],
            [
                'title' => 'Youth Ministry Handbook',
                'description' => 'Complete handbook for youth ministry leaders including activities, safety guidelines, and spiritual development resources.',
                'type' => 'pdf',
                'category' => 'youth',
                'url' => 'https://docs.google.com/document/d/example4',
                'is_public' => false,
                'uploaded_by' => $adminUser->id,
            ],
            [
                'title' => 'Children\'s Sunday School Curriculum',
                'description' => 'Age-appropriate curriculum for children\'s Sunday school with lesson plans, activities, and teaching materials.',
                'type' => 'document',
                'category' => 'children',
                'url' => 'https://drive.google.com/folder/d/example5',
                'is_public' => true,
                'uploaded_by' => $adminUser->id,
            ],
            [
                'title' => 'Community Outreach Program Guide',
                'description' => 'Guide for organizing community outreach programs including planning, execution, and follow-up procedures.',
                'type' => 'presentation',
                'category' => 'outreach',
                'url' => 'https://docs.google.com/presentation/d/example6',
                'is_public' => true,
                'uploaded_by' => $adminUser->id,
            ],
            [
                'title' => 'Church Financial Management Guide',
                'description' => 'Comprehensive guide for church financial management including budgeting, accounting, and reporting procedures.',
                'type' => 'document',
                'category' => 'administration',
                'url' => 'https://docs.google.com/spreadsheets/d/example7',
                'is_public' => false,
                'uploaded_by' => $adminUser->id,
            ],
            [
                'title' => 'Prayer Meeting Guidelines',
                'description' => 'Guidelines for organizing and conducting effective prayer meetings in the church.',
                'type' => 'document',
                'category' => 'general',
                'url' => 'https://docs.google.com/document/d/example8',
                'is_public' => true,
                'uploaded_by' => $adminUser->id,
            ],
            [
                'title' => 'Sermon Archive 2024',
                'description' => 'Collection of recorded sermons from 2024 with transcripts and study notes.',
                'type' => 'video',
                'category' => 'sermons',
                'url' => 'https://www.youtube.com/playlist?list=example9',
                'is_public' => true,
                'uploaded_by' => $adminUser->id,
            ],
            [
                'title' => 'Church Constitution and Bylaws',
                'description' => 'Official church constitution and bylaws document outlining church governance and policies.',
                'type' => 'pdf',
                'category' => 'administration',
                'url' => 'https://drive.google.com/file/d/example10',
                'is_public' => false,
                'uploaded_by' => $adminUser->id,
            ],
            [
                'title' => 'Worship Team Training Materials',
                'description' => 'Training materials for worship team members including technical guides and spiritual preparation.',
                'type' => 'document',
                'category' => 'worship',
                'url' => 'https://drive.google.com/folder/d/example11',
                'is_public' => false,
                'uploaded_by' => $adminUser->id,
            ],
            [
                'title' => 'Bible Study: Book of Romans',
                'description' => 'In-depth study guide for the Book of Romans with theological insights and practical applications.',
                'type' => 'pdf',
                'category' => 'bible-study',
                'url' => 'https://docs.google.com/document/d/example12',
                'is_public' => true,
                'uploaded_by' => $adminUser->id,
            ],
        ];

        foreach ($resources as $resourceData) {
            Resource::create($resourceData);
        }
    }
}
