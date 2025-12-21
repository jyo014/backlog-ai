<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // とりあえずテスト用に3つ授業を作る
        \App\Models\Course::create([
            'university_name' => '福岡工業大学', // あなたの大学名に変えてOK
            'course_name' => '基礎ロボット工学',
            'day_of_week' => '月',
            'period' => 1,
        ]);

        \App\Models\Course::create([
            'university_name' => '福岡工業大学',
            'course_name' => '情報工学概論',
            'day_of_week' => '火',
            'period' => 2,
        ]);

        \App\Models\Course::create([
            'university_name' => '福岡工業大学',
            'course_name' => 'システム制御工学',
            'day_of_week' => '水',
            'period' => 3,
        ]);
    }
}
