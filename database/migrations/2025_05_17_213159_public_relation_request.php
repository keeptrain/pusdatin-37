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
            $table->tinyInteger('month_publication');
            $table->date('completed_date');
            $table->date('spesific_date')->nullable();
            $table->string('theme');
            $table->string('target');
            $table->text('links')->nullable();
            $table->tinyInteger('active_checking');
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
