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
        Schema::create('patient_information', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("gender");
            $table->double("height");
            $table->dobule("weight");
            $table->date("birth");
            $table->string("diseases");
            $table->string("previous_treatments");
            // i want this foreignKey to be the primary key of this table
            $table->foreignId("user_id")->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patient_information');
    }
};
