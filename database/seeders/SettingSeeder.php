<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\SettingTranslation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $setting = Setting::create([
            "key" => "company_name",
            "value" => "xxxxxxx",
            "type" => "text",
            "is_translation" => true,
            "is_required" => true,
        ]);
        $setting->translation()->create([
            "local_id" => 1,
            "value" => "xxxxxxx",
        ]);

        $setting = Setting::create([
            "key" => "logo",
            "value" => "xxxxxxx",
            "type" => "text",
            "is_translation" => true,
            "is_required" => true,
        ]);
        $setting->translation()->create([
            "local_id" => 1,
            "value" => "xxxxxxx",
        ]);
        Setting::create([
            "key" => "phone",
            "value" => "02102910129",
            "type" => "number",
            "is_required" => true,
        ]);
    }
}
