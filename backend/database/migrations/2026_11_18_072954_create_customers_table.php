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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('取引先名');
            $table->string('kana')->nullable()->comment('よみがな');
            $table->string('short_name')->nullable()->comment('略称');
            $table->string('address')->nullable()->comment('住所');
            $table->string('tel', 20)->nullable()->comment('電話番号');
            $table->string('fax', 20)->nullable()->comment('FAX番号');

            $table->string('payment_type')->default('closing')->comment('支払条件 請求区分');
            $table->string('payment_closing_day')->nullable()->comment('支払条件 締日');
            $table->string('payment_month')->nullable()->comment('支払条件 支払月');
            $table->string('payment_day')->nullable()->comment('支払条件 支払日');
            $table->string('payment_due_type')->nullable()->comment('支払条件 期限');

            $table->string('billing_type')->default('per_invoice')->comment('請求条件 請求区分');
            $table->string('billing_closing_day')->nullable()->comment('請求条件 締日');
            $table->string('billing_payment_month')->nullable()->comment('請求条件 請求月');
            $table->string('billing_payment_day')->nullable()->comment('請求条件 請求日');
            $table->string('billing_due_type')->nullable()->comment('請求条件 期限');

            $table->foreignId('person_in_charge_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->comment('担当者ユーザーID');

            $table->foreignId('lead_source_id')
                ->nullable()
                ->constrained('lead_sources')
                ->nullOnDelete()
                ->comment('リード獲得元ID');

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
        Schema::dropIfExists('customers');
    }
};
