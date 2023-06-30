<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('User', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('phoneNumber')->nullable();
            $table->string('address')->nullable();
            $table->string('postcode')->nullable();
            $table->string('city')->nullable();
            $table->string('userType')->nullable();
            $table->rememberToken();
            $table->timestamps();
         });
    
        Schema::create('Cart', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userId');
            $table->decimal('cartTotalAmount', 8, 2);
            $table->timestamps();
    
            $table->foreign('userId')->references('id')->on('User');
        });
    

    
        Schema::create('Product', function (Blueprint $table) {
            $table->id();
            $table->string('productName');
            $table->text('productDesc');
            $table->binary('productImage')->nullable();
            $table->timestamps();
        });
        
    
        Schema::create('Variation', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('productId');
            $table->string('productStorage');
            $table->string('productColor');
            $table->integer('qty');
            $table->decimal('productPrice', 8, 2);
            $table->string('productStatus');
            $table->timestamps();
    
            $table->foreign('productId')->references('id')->on('Product');
        });


        Schema::create('Payment', function (Blueprint $table) {
            $table->id();
            $table->float('paymentAmount', 8, 2);
            $table->dateTime('paymentDate');
            $table->string('paymentMethod');
            $table->timestamps();
        });

        Schema::create('Order', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userId');
            $table->unsignedBigInteger('paymentId')->nullable();
            $table->string('orderStatus');
            $table->decimal('totalAmount', 8, 2);
            $table->timestamps();

            $table->foreign('userId')->references('id')->on('User');
            $table->foreign('paymentId')->references('id')->on('Payment');
        });
    
        Schema::create('Order_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('orderId');
            $table->unsignedBigInteger('variationId');
            $table->integer('orderProductQty');
            $table->decimal('subTotal', 8, 2);
            $table->timestamps();
    
            $table->foreign('orderId')->references('id')->on('Order'); 
            $table->foreign('variationId')->references('id')->on('Variation');
        });
    
        Schema::create('Cart_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cartId');
            $table->unsignedBigInteger('variationId');
            $table->integer('cartProductQty');
            $table->decimal('subTotal', 8, 2);
            $table->timestamps();

            $table->foreign('cartId')->references('id')->on('Cart');
            $table->foreign('variationId')->references('id')->on('Variation');
        });
        


        

    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Cart_product');
        Schema::dropIfExists('Order_product');
        Schema::dropIfExists('Order');
        Schema::dropIfExists('Payment');
        Schema::dropIfExists('Variation');
        Schema::dropIfExists('Product');
        Schema::dropIfExists('Cart');
        Schema::dropIfExists('User');
        
    }
};
