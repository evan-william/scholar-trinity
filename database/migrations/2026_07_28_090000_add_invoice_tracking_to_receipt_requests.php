<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('receipt_requests', function (Blueprint $table): void {
            $table->string('invoice_number', 80)->nullable()->after('receipt_number');
            $table->boolean('invoice_received')->default(false)->after('invoice_number');
            $table->boolean('receipt_received')->default(false)->after('invoice_received');
            $table->index('invoice_number');
        });
    }

    public function down(): void
    {
        Schema::table('receipt_requests', function (Blueprint $table): void {
            $table->dropIndex(['invoice_number']);
            $table->dropColumn(['invoice_number', 'invoice_received', 'receipt_received']);
        });
    }
};
