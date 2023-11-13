<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('join_classes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_class_id');
            $table->unsignedBigInteger('student_user_id');
            $table->timestamps();

            $table->foreign('course_class_id')->references('id')->on('course_classes');
            $table->foreign('student_user_id')->references('id')->on('users');
            // Add other foreign key references as needed
            // ...
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('join_classes');
    }
};
