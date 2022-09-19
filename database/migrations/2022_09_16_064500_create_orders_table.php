<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
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
            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('product_id')->references('id')->on('products');
            $table->double('price', 2)->default(0);
            $table->tinyInteger('status');
            $table->timestamp('paid_at')->useCurrent();
            $table->timestamp('expired_at')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
