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
        Schema::create('master_tutorials', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('course_code');
            $table->string('course_name');
            $table->string('url_presentation')->unique();
            $table->string('url_finished')->unique();
            $table->string('creator_email');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_tutorials');
    }
};
