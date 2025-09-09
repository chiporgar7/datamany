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
        Schema::create('transaction_data', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('transaction_id')->index();
            $table->bigInteger('user_id')->index();
            $table->decimal('transaction_value', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_data');
    }
};
