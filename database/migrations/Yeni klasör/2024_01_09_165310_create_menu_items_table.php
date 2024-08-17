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
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Menu::class, 'menu_id')->nullable()->constrained('menus')->cascadeOnDelete();
            $table->text('title')->nullable();
            $table->string('url')->nullable();
            $table->string('target')->nullable();
            $table->string('icon_class')->nullable();
            $table->string('color')->nullable();
            $table->string('route')->nullable();
            $table->integer('order')->nullable()->default(0);
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
        Schema::dropIfExists('menu_items');
    }
};
