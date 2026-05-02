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
        Schema::table('recurring_transactions', function (Blueprint $table) {
            $table->boolean('is_installment')->default(false);
            $table->foreignId('transaction_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('credit_card_account_id')->nullable()->constrained('accounts')->onDelete('cascade');
            $table->decimal('total_amount', 15, 2)->nullable();
            $table->integer('total_months')->nullable();
            $table->integer('remaining_months')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('recurring_transactions', function (Blueprint $table) {
            $table->dropForeign(['transaction_id']);
            $table->dropForeign(['credit_card_account_id']);
            $table->dropColumn([
                'is_installment',
                'transaction_id',
                'credit_card_account_id',
                'total_amount',
                'total_months',
                'remaining_months'
            ]);
        });
    }
};
