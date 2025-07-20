<?php

use App\Models\PublicRelationRequest;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('public_relation_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('status', 86)->default(PublicRelationRequest::getDefaultStates());
            $table->tinyInteger('month_publication');
            $table->date('completed_date');
            $table->date('spesific_date')->nullable();
            $table->string('theme');
            $table->string('target');
            $table->text('links')->nullable();
            $table->tinyInteger('active_checking');
            $table->json('rating')->nullable();
            $table->timestamps();
            $table->softDeletes();

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
        Schema::dropIfExists('public_relation_requests');
    }
};
