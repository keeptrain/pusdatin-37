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
            $table->varchar(255, 'user_id');
            $table->varchar(255, 'category_type');
            $table->unsignedInteger('category_id');
            $table->varchar(255, 'responsible_person');
            $table->varchar(255,'reference_number');
            $table->timestamps();

            /**
             * Add Foreign Key to Users Table
             */
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create('letter_uploads', function (Blueprint $table) {
            $table->id();
            $table->varchar(255, 'file_name');
            $table->varchar(255, 'file_path');
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
        Schema::dropIfExists('letter_upload');
        Schema::dropIfExists('letter_direct');
    }
};
