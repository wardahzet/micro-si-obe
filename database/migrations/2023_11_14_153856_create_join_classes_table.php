<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('join_classes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('course_class_id');
            $table->unsignedBigInteger('student_user_id');
            $table->timestamps();

            // Foreign keys
            $table->foreign('course_class_id')->references('id')->on('course_classes')->onDelete('cascade');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('join_classes');
    }
};
