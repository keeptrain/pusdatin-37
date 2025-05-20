<?php

use App\Models\PublicRelationRequest;
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
        Schema::create('public_relation_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('status', 86)->default(PublicRelationRequest::getDefaultStates());
            $table->date('month_publication')->nullable();
            $table->date('spesific_date')->nullable();
            $table->string('theme');
            // $table->string('media_type');
            $table->string('target');
            $table->boolean('active_review')->default(true);
            $table->text('links')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
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
