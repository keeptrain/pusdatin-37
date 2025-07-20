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
        Schema::create('tracking_historie', function (Blueprint $table) {
            $table->id();
            $table->morphs('requestable');
            $table->string('message', 255);
            $table->text('notes')->nullable();
            $table->string('created_by', 100);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['requestable_id', 'requestable_type'], 'requestable_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tracking_historie');
    }
};
