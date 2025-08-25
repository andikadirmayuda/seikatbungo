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
        Schema::create('custom_bouquets', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('customer_name')->nullable();
            $table->text('description')->nullable();
            $table->decimal('total_price', 12, 2)->default(0);
            $table->string('reference_image')->nullable();
            $table->enum('status', ['draft', 'in_cart', 'ordered'])->default('draft');
            $table->text('special_instructions')->nullable();
            $table->timestamps();
            
            $table->index('status');
            $table->index('customer_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_bouquets');
    }
};
