<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('list_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shopping_list_id')->unsigned();
            $table->string('name');
            $table->unique(array('name', 'shopping_list_id'));
        });

        Schema::table('list_items', function(Blueprint $table) {
            $table->foreign('shopping_list_id')->references('id')->on('shopping_lists')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('list_items', function(Blueprint $table) {
            $table->dropForeign('list_items_shopping_list_id_foreign');
        });

        Schema::dropIfExists('list_items');
    }
}
