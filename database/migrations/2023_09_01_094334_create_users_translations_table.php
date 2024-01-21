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
        Schema::create('users_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('main_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('local_id')->constrained('languages')->restrictOnDelete();
            $table->unique(['local_id','main_id']);
            $table->string("name");
            $table->string("first_name");
            $table->string("last_name");
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
        Schema::dropIfExists('users');
    }
};
