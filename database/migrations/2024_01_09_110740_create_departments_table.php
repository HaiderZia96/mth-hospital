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
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->string('thumbnail_name')->nullable();
            $table->string('thumbnail_url_name')->nullable();
            $table->string('icon_url')->nullable();
            $table->string('icon_name')->nullable();
            $table->string('icon_url_name')->nullable();
            $table->text('description')->nullable();
            $table->string('slug')->nullable();
//            $table->unsignedBigInteger('banner_id')->nullable();
            $table->string('hod_name')->nullable();
            $table->string('hod_designation')->nullable();
            $table->text('hod_message')->nullable();
            $table->string('hod_image_url')->nullable();
            $table->string('hod_image_name')->nullable();
            $table->string('hod_image_url_name')->nullable();
            $table->text('mission')->nullable();
            $table->text('vision')->nullable();
            $table->text('objective')->nullable();
            $table->text('about_us')->nullable();
            $table->string('department_banner_name')->nullable();
            $table->string('department_banner_url')->nullable();
            $table->string('department_banner_url_name')->nullable();
            $table->unsignedBigInteger('priority')->nullable()->unique();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
//            $table->foreign('banner_id')->references('id')->on('department_banners')->onDelete('cascade')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
