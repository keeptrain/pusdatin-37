<?php

use App\Models\Letters\Letter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /**
         * Create the letters table
         */
        Schema::create('letters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title', 255);
            $table->string('responsible_person', 255);
            $table->string('reference_number', 255);
            $table->string('status', 86)->default(Letter::getDefaultStates());
            $table->int('current_division')->default(2);
            // $table->int('previous_division')->nullable();
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
            $table->text('notes')->nullable();
            $table->string('created_by', 100);
            $table->timestamps();
            $table->softDeletes();

            /**
             * Add Foreign Key to Letters Table
             */
            $table->foreign('letter_id')->references('id')->on('letters');
        });

        /**
         * Create the letter_uploads table
         */
        Schema::create('letter_uploads', function (Blueprint $table) {
            $table->id();
            $table->integer('part_number');
            $table->string('file_path');
            $table->integer('version')->default(0);
            $table->boolean('needs_revision')->default(false);
            $table->string('revision_note')->nullable();
            $table->timestamps();
        });

        // /**
        //  * Create the letter_uploads_revisions table
        //  */
        // Schema::create('letter_uploads_revisions', function (Blueprint $table) {
        //     $table->id();
        //     $table->unsignedBigInteger('letter_upload_id');
        //     $table->string('file_path');
        //     $table->integer('version')->default(1);
        //     $table->revision_note('revision_note')->nullable();
        //     $table->timestamps();


        //     $table->foreign('letter_upload_id')->references('id')->on('letter_uploads')->onDelete('cascade');
        // });

        /**
         * Create the letter_directs table
         */
        Schema::create('letter_directs', function (Blueprint $table) {
            $table->id();
            $table->text('body');
            $table->timestamps();
        });

        /**
         * Create the letters_mappings table
         */
        Schema::create('letters_mappings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('letter_id');
            $table->morphs('letterable');

            // Add Foreign Key to Letters Table
            $table->foreign('letter_id')->references('id')->on('letters')->onDelete('cascade');
        });

        Schema::create('letter_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('letter_id');
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade');
            $table->text('body');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            // Add Foreign Key to Letters Table
            $table->foreign('letter_id')->references('id')->on('letters')->onDelete('cascade');
        });

        Schema::create('document_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->int('part_number');
            $table->string('file_path');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
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
        Schema::dropIfExists('letters_mappings');
        Schema::dropIfExists('letter_messages');
        Schema::dropIfExists('document_templates');
    }
};
