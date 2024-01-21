<?php

use App\Http\Requests\BaseRequest;
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
        Schema::create('settings', function (Blueprint $table) {
            //column any image ( type : text ) ( name : Like %png% )...
            //column any file ( type : text ) ( name : Like %pdf% )...
            $table->id();
            $table->string("key")->unique();
            $table->text("value");
            $table->enum("type", BaseRequest::rulesSettings())->default("text");
            $table->boolean("is_translation")->default(false);
            $table->boolean("is_required")->default(true);
            $table->boolean("is_active")->default(true);
            $table->foreignId("created_by")->nullable()->constrained("users")->cascadeOnDelete();
            $table->foreignId("updated_by")->nullable()->constrained("users")->cascadeOnDelete();
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
        Schema::dropIfExists('settings');
    }
};
