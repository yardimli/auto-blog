<?php

	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;

	class CreateUserPageSettingsTable extends Migration
	{
		public function up()
		{
			Schema::create('user_page_settings', function (Blueprint $table) {
				$table->id();
				$table->foreignId('user_id')->constrained()->onDelete('cascade');
				$table->string('page_type'); // home, blog, help, roadmap, feedback, changelog
				$table->string('title')->nullable();
				$table->text('description')->nullable();
				$table->timestamps();

				// Ensure each user can only have one setting per page type
				$table->unique(['user_id', 'page_type']);
			});
		}

		public function down()
		{
			Schema::dropIfExists('user_page_settings');
		}
	}
