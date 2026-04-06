<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Nonaktifkan foreign key
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Category::truncate();
        Product::truncate();
        ProductVariant::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Buat kategori
        $categories = [
            'Alat Baking',
            'Bahan Kue',
            'Topping/Hiasan',
            'Paket'
        ];

        $categoryMap = [];
        foreach ($categories as $cat) {
            $model = Category::firstOrCreate(
                ['slug' => Str::slug($cat)],
                ['name' => $cat]
            );
            $categoryMap[$cat] = $model->id;
        }

        $products = [
            [
                "name" => "Mixer Tangan",
                "image" => "products/mixer.jpeg",
                "category" => "Alat Baking",
                "type" => "single",
                "price" => 450000,
                "stock" => 15,
                "description" => "Mixer tangan listrik untuk membuat adonan kue.",
                "variants" => [["name" => "Standar", "price" => 450000, "stock" => 15]]
            ],
            [
                "name" => "Tepung Terigu Protein Tinggi 1kg",
                "image" => "products/tepung.jpeg",
                "category" => "Bahan Kue",
                "type" => "single",
                "price" => 15000,
                "stock" => 50,
                "description" => "Tepung terigu protein tinggi cocok untuk roti dan kue basah.",
                "variants" => [["name" => "1kg", "price" => 15000, "stock" => 50]]
            ],
            [
                "name" => "Sprinkle Warna-warni 50gr",
                "image" => "products/sprinkle.jpeg",
                "category" => "Topping/Hiasan",
                "type" => "single",
                "price" => 10000,
                "stock" => 200,
                "description" => "Sprinkle dekoratif untuk menghias kue.",
                "variants" => [["name" => "50gr", "price" => 10000, "stock" => 200]]
            ],
            [
                "name" => "Starter Baking Kit",
                "image" => "products/starter.png",
                "category" => "Paket",
                "type" => "package",
                "price" => 250000,
                "stock" => 20,
                "description" => "Paket lengkap bahan dan alat untuk pemula.",
                "variants" => [["name" => "Set Lengkap", "price" => 250000, "stock" => 20]]
            ],
            [
                "name" => "Butter 250gr",
                "image" => "products/butter.jpeg",
                "category" => "Bahan Kue",
                "type" => "single",
                "price" => 30000,
                "stock" => 60,
                "description" => "Butter berkualitas tinggi untuk baking.",
                "variants" => [["name" => "250gr", "price" => 30000, "stock" => 60]]
            ],
            [
                "name" => "Spatula Silikon",
                "image" => "products/spatula.jpeg",
                "category" => "Alat Baking",
                "type" => "single",
                "price" => 15000,
                "stock" => 100,
                "description" => "Spatula silikon tahan panas.",
                "variants" => [["name" => "Standar", "price" => 15000, "stock" => 100]]
            ],
            [
                "name" => "Coklat Chip 100gr",
                "image" => "products/chip.jpeg",
                "category" => "Topping/Hiasan",
                "type" => "single",
                "price" => 12000,
                "stock" => 100,
                "description" => "Coklat chip untuk topping cookies.",
                "variants" => [["name" => "100gr", "price" => 12000, "stock" => 100]]
            ],
            [
                "name" => "Baking Brownies Kit",
                "image" => "products/brownies.png",
                "category" => "Paket",
                "type" => "package",
                "price" => 200000,
                "stock" => 15,
                "description" => "Paket bahan brownies siap pakai.",
                "variants" => [["name" => "Set Brownies", "price" => 200000, "stock" => 15]]
            ],
            [
                "name" => "Baking Powder 45gr",
                "image" => "products/bakingpowder.jpeg",
                "category" => "Bahan Kue",
                "type" => "single",
                "price" => 8000,
                "stock" => 120,
                "description" => "Baking powder untuk membuat kue mengembang.",
                "variants" => [["name" => "45gr", "price" => 8000, "stock" => 120]]
            ],
            [
                "name" => "Rolling Pin Kayu",
                "image" => "products/rollingpin.jpeg",
                "category" => "Alat Baking",
                "type" => "single",
                "price" => 35000,
                "stock" => 50,
                "description" => "Rolling pin kayu untuk meratakan adonan.",
                "variants" => [["name" => "Standar", "price" => 35000, "stock" => 50]]
            ],
            [
                "name" => "Whipped Cream 250ml",
                "image" => "products/whipped.jpeg",
                "category" => "Bahan Kue",
                "type" => "single",
                "price" => 20000,
                "stock" => 80,
                "description" => "Whipped cream siap pakai.",
                "variants" => [["name" => "250ml", "price" => 20000, "stock" => 80]]
            ],
            [
                "name" => "Piping Bag + 5 Nozzle",
                "image" => "products/piping.jpeg",
                "category" => "Alat Baking",
                "type" => "single",
                "price" => 25000,
                "stock" => 60,
                "description" => "Piping bag lengkap dengan nozzle.",
                "variants" => [["name" => "Set", "price" => 25000, "stock" => 60]]
            ],
            [
                "name" => "Fondant 1kg",
                "image" => "products/fondant.jpeg",
                "category" => "Bahan Kue",
                "type" => "single",
                "price" => 90000,
                "stock" => 30,
                "description" => "Fondant untuk dekorasi kue.",
                "variants" => [["name" => "1kg", "price" => 90000, "stock" => 30]]
            ],
            [
                "name" => "Cetakan Cupcake 12pcs",
                "image" => "products/cetakan.jpeg",
                "category" => "Alat Baking",
                "type" => "single",
                "price" => 35000,
                "stock" => 40,
                "description" => "Cetakan cupcake anti lengket.",
                "variants" => [["name" => "12pcs", "price" => 35000, "stock" => 40]]
            ],
            [
                "name" => "Meses Coklat 50gr",
                "image" => "products/meses.jpeg",
                "category" => "Topping/Hiasan",
                "type" => "single",
                "price" => 8000,
                "stock" => 200,
                "description" => "Meses coklat untuk hiasan kue.",
                "variants" => [["name" => "50gr", "price" => 8000, "stock" => 200]]
            ],
            [
                "name" => "Paket Baking Premium",
                "image" => "products/premium.png",
                "category" => "Paket",
                "type" => "package",
                "price" => 400000,
                "stock" => 10,
                "description" => "Paket bahan premium skala kecil.",
                "variants" => [["name" => "Set Premium", "price" => 400000, "stock" => 10]]
            ],
            [
                "name" => "Gula Halus 500gr",
                "image" => "products/gula.jpeg",
                "category" => "Bahan Kue",
                "type" => "single",
                "price" => 12000,
                "stock" => 100,
                "description" => "Gula halus siap pakai.",
                "variants" => [["name" => "500gr", "price" => 12000, "stock" => 100]]
            ],
            [
                "name" => "Cupcake Liner 50pcs",
                "image" => "products/liner.jpeg",
                "category" => "Alat Baking",
                "type" => "single",
                "price" => 12000,
                "stock" => 100,
                "description" => "Cupcake liner siap pakai.",
                "variants" => [["name" => "50pcs", "price" => 12000, "stock" => 100]]
            ],
            [
                "name" => "Almond Slice 50gr",
                "image" => "products/almond.jpeg",
                "category" => "Topping/Hiasan",
                "type" => "single",
                "price" => 15000,
                "stock" => 80,
                "description" => "Almond slice untuk topping.",
                "variants" => [["name" => "50gr", "price" => 15000, "stock" => 80]]
            ],
            [
                "name" => "Baking Cookies Kit",
                "image" => "products/cookies.png",
                "category" => "Paket",
                "type" => "package",
                "price" => 180000,
                "stock" => 25,
                "description" => "Paket lengkap membuat cookies.",
                "variants" => [["name" => "Set Cookies", "price" => 180000, "stock" => 25]]
            ],
            [
                "name" => "Coloring Gel 10ml",
                "image" => "products/coloring.jpeg",
                "category" => "Topping/Hiasan",
                "type" => "single",
                "price" => 12000,
                "stock" => 150,
                "description" => "Pewarna makanan gel.",
                "variants" => [["name" => "10ml", "price" => 12000, "stock" => 150]]
            ],
            [
                "name" => "Spray Coklat",
                "image" => "products/spray.jpeg",
                "category" => "Topping/Hiasan",
                "type" => "single",
                "price" => 25000,
                "stock" => 60,
                "description" => "Spray coklat untuk dekorasi.",
                "variants" => [["name" => "50ml", "price" => 25000, "stock" => 60]]
            ],
            [
                "name" => "Susu Bubuk 200gr",
                "image" => "products/susu.jpeg",
                "category" => "Bahan Kue",
                "type" => "single",
                "price" => 15000,
                "stock" => 90,
                "description" => "Susu bubuk untuk campuran kue.",
                "variants" => [["name" => "200gr", "price" => 15000, "stock" => 90]]
            ],
            [
                "name" => "Paket Kue Natal",
                "image" => "products/natal.png",
                "category" => "Paket",
                "type" => "package",
                "price" => 300000,
                "stock" => 12,
                "description" => "Paket spesial baking tema Natal.",
                "variants" => [["name" => "Set Natal", "price" => 300000, "stock" => 12]]
            ]
        ];

        foreach ($products as $p) {
            $product = Product::create([
                'name' => $p['name'],
                'image' => $p['image'], // path relatif ke storage/app/public
                'description' => $p['description'],
                'category_id' => $categoryMap[$p['category']],
                'type' => $p['type'],
                'price' => $p['price'],
                'stock' => $p['stock'],
            ]);

            foreach ($p['variants'] as $v) {
                $product->variants()->create($v);
            }
        }
    }
}