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
    Schema::create('grn_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('grn_id')->constrained('grns')->cascadeOnDelete();
        $table->string('item_code')->nullable();
        $table->string('item_name');
        $table->integer('quantity')->unsigned()->default(0);
        $table->decimal('price_per_unit', 12, 2)->default(0);
        $table->decimal('total_price', 12, 2)->default(0);
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
        Schema::dropIfExists('grn_items');
    }
};
