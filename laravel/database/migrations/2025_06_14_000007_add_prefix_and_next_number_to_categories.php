<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('prefix', 5)->after('name')->nullable();
            $table->integer('next_number')->after('prefix')->default(1);
        });

        // Define category prefixes
        $prefixes = [
            'BP' => 'Bunga Potong',
            'BA' => 'Bunga Artificial',
            'BQ' => 'Bouquet',
            'D'  => 'Daun'
        ];

        // Update existing categories with prefix
        foreach ($prefixes as $prefix => $name) {
            DB::table('categories')
                ->where('name', $name)
                ->update([
                    'prefix' => $prefix,
                    'next_number' => 1
                ]);
        }

        // Add unique constraint after data is updated
        Schema::table('categories', function (Blueprint $table) {
            $table->unique('prefix');
        });
    }

    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropUnique(['prefix']);
            $table->dropColumn(['prefix', 'next_number']);
        });
    }
};
