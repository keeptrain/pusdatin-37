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
            $table->integer('current_revision')->default(0);
            $table->boolean('active_revision')->default(false);
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
            $table->text('notes', 255)->nullable();
            $table->string('created_by', 100);
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

        // // Create letter_revisions table to store revision history
        // Schema::create('letter_revisions', function (Blueprint $table) {
        //     $table->id();
        //     $table->unsignedBigInteger('letter_id');
        //     $table->unsignedBigInteger('revised_by'); // User who made the revision
        //     $table->integer('revision_number');
        //     $table->string('title', 255)->nullable();
        //     $table->string('responsible_person', 255)->nullable();
        //     $table->string('reference_number', 255)->nullable();
        //     $table->text('revision_notes')->nullable(); // Notes explaining the revision
        //     $table->text('changes_json')->nullable(); // Store changes in JSON format
        //     $table->timestamps();

        //     // Add Foreign Keys
        //     $table->foreign('letter_id')->references('id')->on('letters');
        //     $table->foreign('revised_by')->references('id')->on('users');
        // });
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
