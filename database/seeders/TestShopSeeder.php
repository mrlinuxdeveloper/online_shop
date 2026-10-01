<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Size;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TestShopSeeder extends Seeder
{
    public function run(): void
    {
        /*
         * Category
         */
        $category = Category::firstOrCreate(
            [
                'slug' => 'test-clothing',
            ],
            [
                'parent_id' => null,
                'name' => 'Test Clothing',
                'description' => 'Test category for online shop',
                'image' => null,
                'status' => true,
            ]
        );

        /*
         * Brand
         */
        $brand = Brand::firstOrCreate(
            [
                'slug' => 'test-brand',
            ],
            [
                'name' => 'Test Brand',
                'logo' => null,
                'description' => 'Test brand',
                'status' => true,
            ]
        );

        /*
         * Sizes
         */
        $sizes = [];

        foreach ([
                     ['name' => 'Small', 'code' => 'S'],
                     ['name' => 'Medium', 'code' => 'M'],
                     ['name' => 'Large', 'code' => 'L'],
                     ['name' => 'X-Large', 'code' => 'XL'],
                 ] as $sizeData) {

            $sizes[$sizeData['code']] = Size::firstOrCreate(
                [
                    'code' => $sizeData['code'],
                ],
                [
                    'name' => $sizeData['name'],
                ]
            );
        }

        /*
         * Colors
         */
        $colors = [];

        foreach ([
                     [
                         'name' => 'Black',
                         'code' => 'BLACK',
                         'hex' => '#000000',
                     ],
                     [
                         'name' => 'White',
                         'code' => 'WHITE',
                         'hex' => '#FFFFFF',
                     ],
                     [
                         'name' => 'Blue',
                         'code' => 'BLUE',
                         'hex' => '#0000FF',
                     ],
                 ] as $colorData) {

            $colors[$colorData['code']] = Color::firstOrCreate(
                [
                    'code' => $colorData['code'],
                ],
                [
                    'name' => $colorData['name'],
                    'hex' => $colorData['hex'],
                ]
            );
        }

        /*
         * Product 1
         */
        $tshirt = Product::firstOrCreate(
            [
                'slug' => 'test-basic-t-shirt',
            ],
            [
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'name' => 'Basic T-Shirt',
                'product_code' => 'TEST-TSHIRT-001',
                'description' => 'Test T-Shirt for online shop.',
                'price' => 500000,
                'discount' => 0,
                'status' => true,
                'featured' => true,
            ]
        );

        /*
         * T-Shirt Variants
         */
        $tshirtVariants = [
            ['size' => 'M', 'color' => 'BLACK', 'stock' => 10],
            ['size' => 'L', 'color' => 'BLACK', 'stock' => 8],
            ['size' => 'M', 'color' => 'WHITE', 'stock' => 6],
            ['size' => 'L', 'color' => 'WHITE', 'stock' => 5],
            ['size' => 'XL', 'color' => 'BLUE', 'stock' => 4],
        ];

        foreach ($tshirtVariants as $variantData) {

            ProductVariant::firstOrCreate(
                [
                    'sku' =>
                        'TEST-TS-' .
                        $variantData['size'] .
                        '-' .
                        $variantData['color'],
                ],
                [
                    'product_id' => $tshirt->id,
                    'size_id' => $sizes[$variantData['size']]->id,
                    'color_id' => $colors[$variantData['color']]->id,
                    'price' => 500000,
                    'stock' => $variantData['stock'],
                    'weight' => 0.30,
                    'status' => true,
                ]
            );
        }

        /*
         * Product 2
         */
        $hoodie = Product::firstOrCreate(
            [
                'slug' => 'test-classic-hoodie',
            ],
            [
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'name' => 'Classic Hoodie',
                'product_code' => 'TEST-HOODIE-001',
                'description' => 'Test Hoodie for online shop.',
                'price' => 900000,
                'discount' => 10,
                'status' => true,
                'featured' => false,
            ]
        );

        /*
         * Hoodie Variants
         */
        $hoodieVariants = [
            ['size' => 'M', 'color' => 'BLACK', 'stock' => 7],
            ['size' => 'L', 'color' => 'BLACK', 'stock' => 5],
            ['size' => 'XL', 'color' => 'BLUE', 'stock' => 3],
        ];

        foreach ($hoodieVariants as $variantData) {

            ProductVariant::firstOrCreate(
                [
                    'sku' =>
                        'TEST-HD-' .
                        $variantData['size'] .
                        '-' .
                        $variantData['color'],
                ],
                [
                    'product_id' => $hoodie->id,
                    'size_id' => $sizes[$variantData['size']]->id,
                    'color_id' => $colors[$variantData['color']]->id,
                    'price' => 900000,
                    'stock' => $variantData['stock'],
                    'weight' => 0.70,
                    'status' => true,
                ]
            );
        }

        $this->command->info(
            'Test shop data created successfully.'
        );
    }
}
