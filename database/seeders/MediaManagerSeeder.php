<?php

namespace Database\Seeders;

use App\Models\MediaManager;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MediaManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <1000 ; $i++){
            MediaManager::create([
                "file_name"  => $i ,
                "file_size"  => $i ,
                "extension"  => $i ,
                "object_name"  => $i ,
                "pdf_path"  => $i ,
                "type"  =>"file" ,
            ]);
        }
    }
}
