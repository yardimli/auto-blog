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
        Schema::create('release_notes', function (Blueprint $table) {
          $table->engine('InnoDB');
          $table->id();
          $table->integer('user_id')->index()->default(0);
          $table->string('title');
          $table->boolean('is_released')->default(false);
          $table->timestamp('released_at')->nullable();
          $table->longText('body');  // for markdown content
          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('release_notes');
    }
};
