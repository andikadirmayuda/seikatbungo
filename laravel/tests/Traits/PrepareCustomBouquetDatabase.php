<?php

namespace Tests\Traits;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

trait PrepareCustomBouquetDatabase
{
    protected function setupCustomBouquetTable()
    {
        if (!Schema::hasTable('custom_bouquets')) {
            Schema::create('custom_bouquets', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('customer_name')->nullable();
                $table->text('description')->nullable();
                $table->decimal('total_price', 10, 2)->default(0);
                $table->string('ribbon_color')->nullable();
                $table->string('status')->default('draft');
                $table->text('special_instructions')->nullable();
                $table->timestamps();
            });
        }
    }

    protected function cleanupCustomBouquetTable()
    {
        Schema::dropIfExists('custom_bouquets');
    }
}
