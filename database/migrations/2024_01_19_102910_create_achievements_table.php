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
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->text('name')->nullable();
            $table->string('slug')->nullable();
            $table->string('image_url')->nullable();
            $table->string('image_name')->nullable();
            $table->string('image_url_name')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->on('users')->onDelete('cascade')->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->on('users')->onDelete('cascade')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};
