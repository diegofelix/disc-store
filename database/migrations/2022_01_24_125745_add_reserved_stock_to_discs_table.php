<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReservedStockToDiscsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('discs', function (Blueprint $table) {
            $table->unsignedBigInteger('reserved_stock')
                ->nullable()
                ->default(0)
                ->after('stock');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('discs', function (Blueprint $table) {
            $table->dropColumn('reserved_stock');
        });
    }
}
