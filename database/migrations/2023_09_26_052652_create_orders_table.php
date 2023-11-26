<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->date('order_date');
            $table->integer('quantity');
            $table->string('detail');
            $table->string('status');
            $table->string('payment_proof');
            // $table->bigInteger('user_id');
            // $table->bigInteger('menu_id');

            $table->foreignId('user_id')->constrained();
            $table->foreignId('menu_id')->constrained();
            $table->foreignId('allocation_id')->constrained();

            // $table->foreign('user_id')->references('id')->on('users');
            // $table->foreign('menu_id')->references('id')->on('menus');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
