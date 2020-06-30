<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('confirmed')->nullable();
            $table->string('deaths')->nullable();
            $table->string('recovered')->nullable();

            // Used To Detect & Display Daily New Cases (Increases/Decreases etc...)
            $table->string('latest_confirmed')->nullable(); // "Latest" Means The Number Recorded Before Last Update
            $table->string('latest_deaths')->nullable();
            $table->string('latest_recovered')->nullable();

            // Timestamps Used To Detect Updates And Their Intervals
            $table->timestamp('latest_confirmed_update')->nullable();
            $table->timestamp('latest_deaths_update')->nullable();
            $table->timestamp('latest_recovered_update')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('countries');
    }
}
