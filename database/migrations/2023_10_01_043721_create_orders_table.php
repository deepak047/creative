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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('status')->default(0);
            $table->integer('user_id')->unsigned()->nullable();
            $table->string('payment_method')->nullable();
         

            $table->integer('total_item_count')->nullable();
            $table->integer('total_qty_ordered')->nullable();

            $table->string('base_currency_code')->nullable();
            $table->string('channel_currency_code')->nullable();
            $table->string('order_currency_code')->nullable();

            $table->decimal('grand_total', 12, 4)->default(0)->nullable();
            $table->decimal('base_grand_total', 12, 4)->default(0)->nullable();
            $table->decimal('grand_total_invoiced', 12, 4)->default(0)->nullable();
            $table->decimal('base_grand_total_invoiced', 12, 4)->default(0)->nullable();
            $table->decimal('grand_total_refunded', 12, 4)->default(0)->nullable();
            $table->decimal('base_grand_total_refunded', 12, 4)->default(0)->nullable();

            $table->decimal('sub_total', 12, 4)->default(0)->nullable();
            $table->decimal('base_sub_total', 12, 4)->default(0)->nullable();
            $table->decimal('sub_total_invoiced', 12, 4)->default(0)->nullable();
            $table->decimal('base_sub_total_invoiced', 12, 4)->default(0)->nullable();
            $table->decimal('sub_total_refunded', 12, 4)->default(0)->nullable();
            $table->decimal('base_sub_total_refunded', 12, 4)->default(0)->nullable();

            $table->decimal('tax_amount', 12, 4)->default(0)->nullable();
            $table->decimal('base_tax_amount', 12, 4)->default(0)->nullable();
            $table->decimal('tax_amount_invoiced', 12, 4)->default(0)->nullable();
            $table->decimal('base_tax_amount_invoiced', 12, 4)->default(0)->nullable();
            $table->decimal('tax_amount_refunded', 12, 4)->default(0)->nullable();
            $table->decimal('base_tax_amount_refunded', 12, 4)->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
