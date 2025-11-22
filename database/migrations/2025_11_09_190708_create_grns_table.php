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
    Schema::create('grns', function (Blueprint $table) {
        $table->id();
        $table->string('grn_number')->unique();
        $table->string('invoice_number')->nullable();
        $table->unsignedBigInteger('supplier_id')->nullable(); // keep simple if suppliers table exists
        $table->date('grn_date')->nullable();
        $table->decimal('total', 12, 2)->default(0);
        $table->decimal('discount_percent', 5, 2)->default(0);
        $table->decimal('net_total', 12, 2)->default(0);
        $table->text('remarks')->nullable();
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
        Schema::dropIfExists('grns');
    }
};
