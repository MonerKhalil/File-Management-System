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
        Schema::create('media_managers', function (Blueprint $table) {
            //column any image ( type : text ) ( name : Like %png% )...
            //column any file ( type : text ) ( name : Like %pdf% )...
            $table->id();
            $table->foreignId("user_id")->constrained("users")->cascadeOnDelete();
            $table->string('file_name')->nullable();
            $table->bigInteger('file_size')->nullable();
            $table->string('pdf_path')->nullable();
            $table->string('object_name')->nullable();
            $table->string('extension')->nullable();
            $table->enum('type',["file","image"]);
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
        Schema::dropIfExists('media_managers');
    }
};
