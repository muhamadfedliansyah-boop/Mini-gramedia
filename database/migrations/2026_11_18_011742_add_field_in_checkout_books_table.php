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
        Schema::table('checkout_books', function (Blueprint $table) {
            $table->foreignId('subscription_checkout_id')->nullable()->constrained
            ('subscription_packages')->onDelete('cascade');

            $table->integer('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('checkout_books', function (Blueprint $table) {
             $table->dropForeign(['subscription_checkout_id']);
             $table->dropColumn('subscription_package_id');
            $table->dropColumn('price');
        });
    }
};
