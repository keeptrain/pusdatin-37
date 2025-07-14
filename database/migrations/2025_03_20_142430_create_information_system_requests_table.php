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
        });

        /**
         * Create the system_request_messages table
         */
        Schema::create('request_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_id');
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade');
            $table->text('body');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            // Add Foreign Key to Information System Requests Table
            $table->foreign('request_id')->references('id')->on('information_system_requests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('information_system_requests');
        Schema::dropIfExists('request_messages');
    }
};
