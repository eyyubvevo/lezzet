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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->text('title')->nullable();
            $table->text('slug')->nullable();
            $table->longText('content')->nullable();
            $table->decimal('price',8,2);
            $table->decimal('discount',8,2);
            $table->integer('order')->default(0);
            $table->boolean('status')->default(true);
            $table->boolean('home_status')->default(false);
            $table->boolean('is_discountable')->default(false);
            $table->foreignIdFor(\App\Models\Attribute::class, 'attribute_id')->nullable()->constrained('attributes')->cascadeOnDelete();
            $table->timestamps();
            $table->timestamp('discount_start_date')->nullable();
            $table->timestamp('discount_end_date')->nullable();
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
};
