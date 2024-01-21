<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Language::query()->delete();
        Language::create([
            'code' => 'en',
            'name' => 'English',
            'default' => true,
        ]);
        Language::create([
            'code' => 'ar',
            'name' => 'العربية',
            'isRTL' => true,
        ]);
    }
}
