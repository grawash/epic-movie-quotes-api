<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('movies', function (Blueprint $table) {
			$table->id();
			$table->foreignId('user_id');
			$table->json('title');
			$table->string('slug')->unique();
			$table->json('director');
			$table->json('description');
			$table->string('thumbnail')->nullable();
			$table->timestamps();
			$table->timestamp('published_at')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('movies');
	}
};
