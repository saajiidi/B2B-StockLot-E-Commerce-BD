<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('quotation_number')->unique();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('buyer_id');
            $table->unsignedBigInteger('seller_id');
            
            $table->enum('status', ['pending', 'accepted', 'rejected', 'expired'])
                  ->default('pending');
            
            $table->decimal('quoted_price', 12, 2);
            $table->decimal('original_price', 12, 2);
            $table->decimal('discount_percentage', 5, 2)->default(0);
            
            $table->text('notes')->nullable();
            $table->timestamp('valid_until')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('buyer_id')->references('id')->on('users');
            $table->foreign('seller_id')->references('id')->on('users');
            $table->index(['status', 'valid_until']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quotations');
    }
}
