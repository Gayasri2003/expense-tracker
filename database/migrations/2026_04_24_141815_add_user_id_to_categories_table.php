<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // null = default/system category, not null = user-defined
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade')->after('id');
            $table->boolean('is_default')->default(false)->after('color');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\User::class);
            $table->dropColumn(['user_id', 'is_default']);
        });
    }
};
