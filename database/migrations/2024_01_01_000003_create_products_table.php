<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->string('sku')->unique();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('user_id'); // seller
            
            // Pricing
            $table->decimal('price', 12, 2);
            $table->decimal('bulk_price_1', 12, 2)->nullable();
            $table->decimal('bulk_price_2', 12, 2)->nullable();
            $table->decimal('bulk_price_3', 12, 2)->nullable();
            $table->integer('bulk_qty_1')->nullable();
            $table->integer('bulk_qty_2')->nullable();
            $table->integer('bulk_qty_3')->nullable();
            
            // Inventory
            $table->integer('stock_quantity')->default(0);
            $table->integer('min_order_quantity')->default(1);
            
            // Product Details
            $table->decimal('weight', 8, 2)->nullable();
            $table->string('dimensions')->nullable();
            $table->string('material')->nullable();
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->string('brand')->nullable();
            $table->string('country_of_origin')->default('Bangladesh');
            
            // Flags
            $table->boolean('is_stocklot')->default(false);
            $table->boolean('is_active')->default(true);
            
            // Media
            $table->string('featured_image')->nullable();
            $table->json('gallery_images')->nullable();
            
            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('user_id')->references('id')->on('users');
            $table->index(['is_active', 'is_stocklot']);
            $table->index(['category_id', 'is_active']);
            $table->index(['user_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
