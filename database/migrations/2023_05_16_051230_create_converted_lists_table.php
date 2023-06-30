<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConvertedListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('converted_lists', function (Blueprint $table) {
            $table->id();
            $table->string('user_input');
            $table->string('wn_conversion');
            $table->string('usd_conversion'); //string because we will also insert error messages
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
        Schema::dropIfExists('converted_lists');
    }
}
