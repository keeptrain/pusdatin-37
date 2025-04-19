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
        Schema::create('letters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->morphs('letterable');
            $table->string('title', 255);
            $table->string('responsible_person', 255);
            $table->string('reference_number', 255);
            $table->string('status', 86)->default('pending');
            $table->timestamps();
            $table->softDeletes();

            /**
             * Add Foreign Key to Users Table
             */
            $table->foreign('user_id')->references('id')->on('users');
        });

        /**
         * Create the request_status_tracks table
         */
        Schema::create('request_status_tracks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('letter_id');
            $table->string('action', 255);
            $table->timestamps();

            /**
             * Add Foreign Key to Letters Table
             */
            $table->foreign('letter_id')->references('id')->on('letters');
        });

        /**
         * Create the letter_uploads and letter_directs tables
         */
        Schema::create('letter_uploads', function (Blueprint $table) {
            $table->id();
            $table->string('file_name');
            $table->string('file_path');
        });

        Schema::create('letter_directs', function (Blueprint $table) {
            $table->id();
            $table->text('body');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('letters');
        Schema::dropIfExists('request_status_tracks');
        Schema::dropIfExists('letter_uploads');
        Schema::dropIfExists('letter_directs');
    }
};
