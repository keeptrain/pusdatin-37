<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('meeting_information_system_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('request_id');
            $table->string('topic');
            $table->json('place');
            $table->datetime('start_at');
            $table->datetime('end_at');
            $table->json('recipients')->nullable();
            $table->text('result')->nullable();

            $table->foreign('request_id')->references('id')->on('information_system_requests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meeting_information_system_requests');
    }
};
