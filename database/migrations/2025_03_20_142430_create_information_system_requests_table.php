<?php

use App\Models\InformationSystemRequest;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /**
         * Create the information_system_requests table
         */
        Schema::create('information_system_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('status', 86)->default(InformationSystemRequest::getDefaultStates());
            $table->string('title', 255);
            $table->string('reference_number', 255);
            $table->integer('active_checking');
            $table->integer('current_division')->nullable();
            $table->boolean('active_revision')->default(false);
            $table->boolean('need_review')->default(false);
            $table->json('notes')->nullable();
            $table->json('rating')->nullable();
            $table->timestamps();
            $table->softDeletes();

            /**
             * Add Foreign Key to Users Table
             */
            $table->foreign('user_id')->references('id')->on('users');

            /**
             * Add Index for id and user_id
             */
            $table->index(['id', 'user_id'], 'id_user_id_index');

            /**
             * Add Index for id and status
             */
            $table->index(['id', 'status'], 'id_status_index');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('information_system_requests');
    }
};
