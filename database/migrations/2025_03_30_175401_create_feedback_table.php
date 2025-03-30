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
			Schema::create('feedback', function (Blueprint $table) {
				$table->id();
				$table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Nullable for guest feedback
				$table->string('guest_email')->nullable(); // Optional email for guests
				$table->string('title');
				$table->text('details');
				$table->string('status')->default('Open'); // e.g., Open, Under Review, Planned, Complete, Closed
				$table->integer('votes_count')->default(0); // Cache for vote counts
				$table->timestamps();
			});
		}

		/**
		 * Reverse the migrations.
		 */
		public function down(): void
		{
			Schema::dropIfExists('feedback');
		}
	};
