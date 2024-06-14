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
        Schema::create('news_events', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->unsignedBigInteger('dpt_id')->nullable();
            $table->unsignedBigInteger('e_cate')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->string('thumbnail_name')->nullable();
            $table->string('thumbnail_url_name')->nullable();
            $table->string('banner_url')->nullable();
            $table->string('banner_name')->nullable();
            $table->string('banner_url_name')->nullable();
            $table->string('e_date')->nullable();
            $table->text('short_description')->nullable();
            $table->longtext('long_description')->nullable();
            $table->text('tag')->nullable();
            $table->unsignedBigInteger('priority')->nullable()->unique();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('e_cate')->references('id')->on('event_category')->onDelete('cascade')->nullable();
            $table->foreign('dpt_id')->references('id')->on('departments')->onDelete('cascade')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade')->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_events');
    }
};
