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
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('symptoms');
            $table->string('state')->default("pending");
            $table->string('prescriptions')->nullable();
            // must have a patient that is in users table, but doctor may be null
            // doubt: how to delete only pending sumbissions when patient delete his/her account?
            $table->foreignId('patient_id')->constrained('users');
            $table->foreignId('doctor_id')->nullable()->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('submissions');
    }
};
