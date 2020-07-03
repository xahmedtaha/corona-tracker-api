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

            // Totals
            $table->bigInteger('confirmed')->nullable();
            $table->bigInteger('deaths')->nullable();
            $table->bigInteger('recovered')->nullable();
            $table->bigInteger('tests')->nullable();
            $table->bigInteger('population')->nullable();

            // "new" Means The Latest Cases Update, "old" Means The Update Before That One (Used For Statistics)
            $table->bigInteger('new_confirmed')->nullable();
            $table->bigInteger('new_deaths')->nullable();
//            $table->bigInteger('new_recovered')->nullable();

//            $table->bigInteger('old_confirmed')->nullable();
//            $table->bigInteger('old_deaths')->nullable();
//            $table->bigInteger('old_recovered')->nullable();

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
