<?php

use App\Http\Requests\BaseRequest;
use App\Models\SettingControl;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Arr;
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
        Schema::create('setting_controls', function (Blueprint $table) {
            //column any image ( type : text ) ( name : Like %png% )...
            //column any file ( type : text ) ( name : Like %pdf% )...
            $table->id();
            $table->enum("category", SettingControl::TYPES)->default("general");
            $table->string("key")->unique();
            $table->text("value");
            $table->enum("type", Arr::except(BaseRequest::rulesSettings(),["file","image","editor"]))->default("text");
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
        Schema::dropIfExists('setting_controls');
    }
};
