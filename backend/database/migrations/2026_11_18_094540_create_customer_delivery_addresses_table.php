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
        Schema::create('customer_delivery_addresses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')
                ->constrained('customers')
                ->cascadeOnDelete()
                ->comment('取引先ID');

            $table->string('name')->comment('出荷元、納品先名');
            $table->string('contact_name')->nullable()->comment('納品先担当者名');

            $table->string('address')->nullable()->comment('住所');
            $table->string('tel', 50)->nullable()->comment('TEL');

            $table->text('remarks')->nullable()->comment('備考');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_delivery_addresses');
    }
};
