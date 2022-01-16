<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discs', function (Blueprint $table) {
            $table->id();
            $table->string('name');

            // these fields can be transformed into a
            // foreign key as the ecommerce grows.
            $table->string('artist');
            $table->string('style');


            // can be transformed into a foreign key as the ecommerce grows
            // As we cannot sell half of a disc, we will make
            // as unsigned biginteger.
            $table->unsignedBigInteger('stock');

            $table->timestamp('released_at');
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
        Schema::dropIfExists('discs');
    }
}
