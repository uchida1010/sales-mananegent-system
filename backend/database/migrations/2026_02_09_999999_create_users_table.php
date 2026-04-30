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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->nullable()->index()->comment('部署ID');
            $table->string('user_code')->unique()->comment('ユーザーID');
            $table->string('name')->comment('ユーザー名');
            $table->string('name_kana')->nullable()->comment('よみがな');
            $table->string('email')->unique()->comment('メールアドレス');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone', 20)->nullable()->comment('電話番号');
            $table->string('password')->comment('パスワード');
            $table->string('position')->comment('役職');
            $table->string('status', 20)->index()->comment('雇用状態: active=在職, leave=休職, resigned=退職');
            $table->date('joined_at')->comment('入社日');
            $table->date('resigned_at')->nullable()->comment('退職日');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->comment('作成者');
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->comment('更新者');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
