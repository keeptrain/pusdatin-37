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
        /**
         * Create the letter_uploads table
         */
        Schema::create('document_uploads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_upload_version_id')->nullable();
            $table->morphs('documentable');
            $table->tinyInteger('part_number');
            $table->boolean('need_revision')->default(false);
            $table->timestamps();
        });

        /**
         * Create the letter_uploads_revisions table
         */
        Schema::create('document_upload_versions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_upload_id');
            $table->string('file_path')->nullable();
            $table->tinyInteger('version')->default(0);
            $table->string('revision_note')->nullable();
            $table->boolean('is_resolved')->default(false);
            $table->timestamps();

            /**
             * Add Foreign Key to Letter uploads Table
             */
            $table->foreign('document_upload_id')->references('id')->on('document_uploads')->onDelete('cascade');
        });

        Schema::table('document_uploads', function (Blueprint $table) {
            $table->foreign('document_upload_version_id')->references('id')->on('document_upload_versions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_uploads');
        Schema::dropIfExists('document_upload_versions');
    }
};
