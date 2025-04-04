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
            $table->string('category_type', 255);
            $table->unsignedInteger('category_id');
            $table->string('responsible_person', 255);
            $table->string('reference_number', 255);
            $table->enum('status', ['New', 'Read', 'Replied', 'Closed']);
            $table->timestamps();
            $table->softDeletes();

            /**
             * Add Foreign Key to Users Table
             */
            $table->foreign('user_id')->references('id')->on('users');
        });

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
        Schema::dropIfExists('letter_uploads');
        Schema::dropIfExists('letter_directs');
    }
};
