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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default(NULL);
            $table->integer('user_id')->default(0);
            $table->date('date')->nullable()->default(null);
            $table->string('voucher')->nullable()->default(null);
            $table->string('item')->nullable()->default(null);
            $table->string('transaction_id')->nullable(); // Define your new column here
            $table->integer('qty')->default(0);
            $table->string('description')->default(NULL);
            $table->integer('type')->default(0);
            $table->string('payment_type')->nullable()->default(null);
            $table->decimal('amount', 10, 2)->nullable()->default(null);
            $table->decimal('price', 10, 2)->nullable()->default(null);
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
        Schema::dropIfExists('stocks');
    }
};
