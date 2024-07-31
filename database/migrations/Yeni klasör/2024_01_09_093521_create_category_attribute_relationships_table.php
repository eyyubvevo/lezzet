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
        Schema::create('category_attribute_relationships', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Attribute::class, 'attribute_id')->nullable()->constrained('attributes')->cascadeOnDelete();
//            $table->foreignIdFor(\AliBayat\LaravelCategorizable\Category::class, 'category_id')->nullable()->constrained('categories')->cascadeOnDelete();
            $table->integer('category_id');
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
        Schema::dropIfExists('category_attribute_relationships');
    }
};
