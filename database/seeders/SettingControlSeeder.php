<?php

namespace Database\Seeders;

use App\HelperClasses\MyApp;
use App\HelperClasses\StorageFiles;
use App\Models\SettingControl;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingControlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SettingControl::create([
            "category" => "general",
            "key" => "APP_NAME",
            "value" => "Laravel",
            "type" => "text",
            "is_required" => true,
        ]);
        SettingControl::create([
            "category" => "system",
            "key" => "PAGINATE_DEFAULT",
            "value" => MyApp::DEFAULT_PAGES_Count,
            "type" => "number",
            "is_required" => true,
        ]);
        SettingControl::create([
            "category" => "system",
            "key" => "Ex_FILE",
            "value" => MyApp::Classes()->storageFiles->getExFiles(),
            "type" => "text",
            "is_required" => true,
        ]);
        SettingControl::create([
            "category" => "system",
            "key" => "SIZE_FILES_STORAGE",
            "value" => MyApp::Classes()->storageFiles->getSizeFiles(),
            "type" => "number",
            "is_required" => true,
        ]);
        SettingControl::create([
            "category" => "system",
            "key" => "EX_IMG",
            "value" => MyApp::Classes()->storageFiles->getExImages(),
            "type" => "text",
            "is_required" => true,
        ]);
        SettingControl::create([
            "category" => "system",
            "key" => "SIZE_IMAGES_STORAGE",
            "value" => MyApp::Classes()->storageFiles->getSizeImages(),
            "type" => "number",
            "is_required" => true,
        ]);
    }
}
