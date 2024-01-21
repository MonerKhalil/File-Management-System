<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            //column any image ( type : text ) ( name : Like %png% )...
            //column any file ( type : text ) ( name : Like %pdf% )...
            $table->id();
            $table->foreignId("id_user")->constrained("users",'id')->cascadeOnDelete();
            $table->foreignId("id_media_manager")->constrained("media_managers",'id')->cascadeOnDelete();
            $table->string("name")->unique();
            $table->boolean("is_active")->default(true);
            $table->foreignId("created_by")->nullable()->constrained("users")->cascadeOnDelete();
            $table->foreignId("updated_by")->nullable()->constrained("users")->cascadeOnDelete();
            $table->text("notes")->nullable();
            $table->timestamps();
            $table->timestamp("deleted_at")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
};
