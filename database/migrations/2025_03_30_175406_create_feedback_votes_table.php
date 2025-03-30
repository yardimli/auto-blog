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
			Schema::create('feedback_votes', function (Blueprint $table) {
				$table->id();
				$table->foreignId('feedback_id')->constrained()->onDelete('cascade');
				$table->foreignId('user_id')->constrained()->onDelete('cascade');
				$table->timestamps();

				// Prevent duplicate votes by the same user on the same feedback
				$table->unique(['feedback_id', 'user_id']);
			});
		}

		/**
		 * Reverse the migrations.
		 */
		public function down(): void
		{
			Schema::dropIfExists('feedback_votes');
		}
	};
