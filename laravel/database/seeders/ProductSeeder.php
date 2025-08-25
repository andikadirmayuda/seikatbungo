<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductPrice;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get Fresh Flowers category
        $freshFlowersCategory = Category::where('code', 'FF')->first();

        if (!$freshFlowersCategory) {
            $this->command->error('Fresh Flowers category not found. Please run CategorySeeder first.');
            return;
        }

        $products = [
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => 'FF001',
                    'name' => 'Mawar Merah',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 15000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_20',
                        'price' => 150000.00,
                        'unit_equivalent' => 20,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 99500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '1325282930',
                    'name' => 'Mawar Ungu',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 15000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_20',
                        'price' => 150000.00,
                        'unit_equivalent' => 20,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 99500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '938595803',
                    'name' => 'Mawar Biru',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 15000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_20',
                        'price' => 150000.00,
                        'unit_equivalent' => 20,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 99500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '34543535232',
                    'name' => 'Mawar Putih',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 13000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_20',
                        'price' => 130000.00,
                        'unit_equivalent' => 20,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 85000.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '0494049999990',
                    'name' => 'Mawar Pink',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 13000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_20',
                        'price' => 130000.00,
                        'unit_equivalent' => 20,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 85000.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '34543535232',
                    'name' => 'Mawar candy',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 13000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_20',
                        'price' => 130000.00,
                        'unit_equivalent' => 20,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 85000.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '8953958598',
                    'name' => 'Mawar Peach',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 13000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_20',
                        'price' => 130000.00,
                        'unit_equivalent' => 20,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 85000.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '35353532',
                    'name' => 'Mawar Tabur Merah',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'item',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'normal',
                        'price' => 9500.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '2424443434',
                    'name' => 'Mawar Tabur Mix',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'item',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'normal',
                        'price' => 7500.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '8953958598',
                    'name' => 'Mawar Peach',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 13000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_20',
                        'price' => 130000.00,
                        'unit_equivalent' => 20,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 85000.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '78739879993',
                    'name' => 'Aster Putih Jimlah',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 34500.00,
                        'unit_equivalent' => 20,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 30500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '3535235235',
                    'name' => 'Aster Merah Jimlah',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 34500.00,
                        'unit_equivalent' => 20,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 30500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '94343532535',
                    'name' => 'Aster Ungu Jimlah',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 34500.00,
                        'unit_equivalent' => 20,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 30500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '94343532535',
                    'name' => 'Aster Kuning Jimlah',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 34500.00,
                        'unit_equivalent' => 20,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 30500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '94343532535',
                    'name' => 'Aster Orage Jimlah',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 34500.00,
                        'unit_equivalent' => 20,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 30500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '87557857876',
                    'name' => 'Aster Putih Rados',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 34500.00,
                        'unit_equivalent' => 20,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 30500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '22422525',
                    'name' => 'Aster Kuning Rados',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 34500.00,
                        'unit_equivalent' => 20,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 30500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '35353535',
                    'name' => 'Aster Kuning Jimlah',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 34500.00,
                        'unit_equivalent' => 20,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 30500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '35353536373',
                    'name' => 'Aster Pink Jimlah',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 34500.00,
                        'unit_equivalent' => 20,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 30500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '2477727',
                    'name' => 'Matahari',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_5',
                        'price' => 20000.00,
                        'unit_equivalent' => 5,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 30500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '2477727',
                    'name' => 'Aster Batik Remix',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 20000.00,
                        'unit_equivalent' => 10,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 30500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '2477727',
                    'name' => 'Aster Orange Regen',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 34500.00,
                        'unit_equivalent' => 10,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 30500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '2477727',
                    'name' => 'Aster Ungu Regen',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 34500.00,
                        'unit_equivalent' => 10,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 30500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '22436543',
                    'name' => 'Aster Batik Remix',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 34500.00,
                        'unit_equivalent' => 10,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 30500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '22436543',
                    'name' => 'Aster Ungu Berlapis',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 34500.00,
                        'unit_equivalent' => 10,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 30500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '22436543',
                    'name' => 'Aster Lolipop Pink',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 34500.00,
                        'unit_equivalent' => 10,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 30500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '22436543',
                    'name' => 'Aster Lolipop Kuning',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 34500.00,
                        'unit_equivalent' => 10,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 30500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '22436543',
                    'name' => 'Aster Lolipop Orange',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 34500.00,
                        'unit_equivalent' => 10,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 30500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '212853',
                    'name' => 'Hortensia',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 34500.00,
                        'unit_equivalent' => 10,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 30500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '895423',
                    'name' => 'Krisan Putih',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 34500.00,
                        'unit_equivalent' => 10,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 30500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '895423',
                    'name' => 'Krisan Pink',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 34500.00,
                        'unit_equivalent' => 10,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 30500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '653190',
                    'name' => 'Krisan Kuning',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 34500.00,
                        'unit_equivalent' => 10,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 30500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '432864',
                    'name' => 'Krisan Ungu',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 34500.00,
                        'unit_equivalent' => 10,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 30500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '17436894',
                    'name' => 'Krisan Merah',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 35000.00,
                        'unit_equivalent' => 10,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 30500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '17436894',
                    'name' => 'Garbera Merah',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 34500.00,
                        'unit_equivalent' => 10,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 30500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '17436894',
                    'name' => 'Garbera Pink Soft',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 35000.00,
                        'unit_equivalent' => 10,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 30500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '17436894',
                    'name' => 'Garbera Kuning',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 35000.00,
                        'unit_equivalent' => 10,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 30500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '17436894',
                    'name' => 'Garbera Putih',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 35000.00,
                        'unit_equivalent' => 10,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 30500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '17436894',
                    'name' => 'Garbera Putih Pink',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 35000.00,
                        'unit_equivalent' => 10,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 30500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '17436894',
                    'name' => 'Garbera Candy',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 35000.00,
                        'unit_equivalent' => 10,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 30500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '17436894',
                    'name' => 'Garbera Magenta',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 35000.00,
                        'unit_equivalent' => 10,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 30500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '17436894',
                    'name' => 'Calimeru Ungu',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 35000.00,
                        'unit_equivalent' => 10,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 30500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '17436894',
                    'name' => 'Calimeru Kuning',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 35000.00,
                        'unit_equivalent' => 10,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 30500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '17436894',
                    'name' => 'Gompi Pink',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 35000.00,
                        'unit_equivalent' => 10,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 30500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '17436894',
                    'name' => 'Gompi Lemon',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 35000.00,
                        'unit_equivalent' => 10,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 30500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '17436894',
                    'name' => 'Gompi Ungu',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 35000.00,
                        'unit_equivalent' => 10,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 30500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '17436894',
                    'name' => 'Gompi Putih',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 35000.00,
                        'unit_equivalent' => 10,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 30500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '17436894',
                    'name' => 'Gompi Orange',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 35000.00,
                        'unit_equivalent' => 10,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 30500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '17436894',
                    'name' => 'Solidago',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 35000.00,
                        'unit_equivalent' => 10,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 30500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '9823637',
                    'name' => 'Pakis',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 35000.00,
                        'unit_equivalent' => 10,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 30500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '18628608',
                    'name' => 'Sedap Malam',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 35000.00,
                        'unit_equivalent' => 10,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 30500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '2644973',
                    'name' => 'Amarathus',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 35000.00,
                        'unit_equivalent' => 10,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 30500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '264582334',
                    'name' => 'Calla Lily',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 35000.00,
                        'unit_equivalent' => 10,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 30500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '26453286',
                    'name' => 'Amimatus',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 35000.00,
                        'unit_equivalent' => 10,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 30500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '26629647',
                    'name' => 'Pikok Putih',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 23000.00,
                        'unit_equivalent' => 10,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 15500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '28454798',
                    'name' => 'Pikok Ungu',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 23000.00,
                        'unit_equivalent' => 10,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 15500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '863286431',
                    'name' => 'Pikok Pink',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 23000.00,
                        'unit_equivalent' => 10,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 15500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '86913225',
                    'name' => 'Carnation Pink',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 23000.00,
                        'unit_equivalent' => 10,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 15500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '76134489',
                    'name' => 'Carnation Putih',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 23000.00,
                        'unit_equivalent' => 10,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 15500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '7583098',
                    'name' => 'Carnation Candy',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 23000.00,
                        'unit_equivalent' => 10,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 15500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '756231298',
                    'name' => 'Carnation Putih',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 23000.00,
                        'unit_equivalent' => 10,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 15500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '756231298',
                    'name' => 'Carnation Merah',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 23000.00,
                        'unit_equivalent' => 10,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 15500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '328653862',
                    'name' => 'Casablangka Lily',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 23000.00,
                        'unit_equivalent' => 10,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 15500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '32848374',
                    'name' => 'Baby Breath',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 23000.00,
                        'unit_equivalent' => 10,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 15500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '283588362',
                    'name' => 'Tilaspy',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 23000.00,
                        'unit_equivalent' => 10,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 15500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '7257513242',
                    'name' => 'Cinere',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 5000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 23000.00,
                        'unit_equivalent' => 10,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 15500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '7257513242',
                    'name' => 'Mawar Candy',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 13000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_20',
                        'price' => 110000.00,
                        'unit_equivalent' => 20,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 85000.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'product_data' => [
                    'category_id' => $freshFlowersCategory->id,
                    'code' => '33635276',
                    'name' => 'Aster Orange Jimla',
                    'description' => 'Kondisi Bagus, Dan Mekar.',
                    'image' => null, // You can add image path later
                    'base_unit' => 'tangkai',
                    'current_stock' => 500,
                    'min_stock' => 10,
                    'is_active' => true,
                ],
                'prices' => [
                    [
                        'type' => 'per_tangkai',
                        'price' => 13000.00,
                        'unit_equivalent' => 1,
                        'is_default' => true,
                    ],
                    [
                        'type' => 'ikat_10',
                        'price' => 34500.00,
                        'unit_equivalent' => 10,
                        'is_default' => false,
                    ],
                    [
                        'type' => 'reseller',
                        'price' => 30500.00,
                        'unit_equivalent' => 1,
                        'is_default' => false,
                    ],
                ],
            ],
        ];

        foreach ($products as $item) {
            $productData = $item['product_data'];
            $pricesData = $item['prices'];

            // Check if product already exists
            $existingProduct = Product::where('code', $productData['code'])->first();

            if (!$existingProduct) {
                // Create product
                $product = Product::create($productData);
                $this->command->info("Product '{$productData['name']}' created successfully.");

                // Create product prices
                foreach ($pricesData as $priceData) {
                    $priceData['product_id'] = $product->id;
                    ProductPrice::create($priceData);
                }
                $this->command->info("Product prices for '{$productData['name']}' created successfully.");
            } else {
                $this->command->info("Product '{$productData['name']}' already exists.");
            }
        }
    }
}
