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

        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('address')->nullable();
            $table->string('phone_no')->nullable();
            $table->string('image_url')->nullable();
            $table->string('image_name')->nullable();
            $table->string('image_url_name')->nullable();
            $table->string('designation')->nullable();
            $table->text('description')->nullable();
            $table->text('education')->nullable();
            $table->text('employment')->nullable();
            $table->text('membership')->nullable();
            $table->text('sitting_time')->nullable();
            $table->text('speciality')->nullable();
            $table->string('slug')->nullable();
            $table->integer('status')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade')->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade')->nullable();
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};
