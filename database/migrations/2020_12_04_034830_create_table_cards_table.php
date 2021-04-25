<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cart_name');
            $table->string('list_id'); // Thuộc list nào 
            $table->string('broad_id'); 
            $table->string('by_user');
            $table->string('order'); // sắp xếp 
            $table->string('check_list'); // danh sách 
            $table->string('time_duo');
            $table->string('members'); // array 
            $table->string('activity'); 
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
        Schema::dropIfExists('cards');
    }
}
