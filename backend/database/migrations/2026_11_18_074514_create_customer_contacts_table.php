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
        Schema::create('customer_contacts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')
                ->constrained('customers')
                ->cascadeOnDelete()
                ->comment('取引先ID');

            $table->string('name')->comment('名前');
            $table->string('tel', 50)->nullable()->comment('TEL');
            $table->string('mobile', 50)->nullable()->comment('携帯');
            $table->string('email')->nullable()->comment('E-mail');
            $table->string('role')->nullable()->comment('役割');
            $table->string('position')->nullable()->comment('役職');

            $table->foreignId('person_in_charge_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->comment('担当者ID');

            $table->boolean('is_active')->default(true)->comment('有効状態');
            $table->text('remarks')->nullable()->comment('備考');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_contacts');
    }
};
