<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan kategori induk tersedia
        $ayamHidup = Category::firstOrCreate(['category' => 'Ayam Hidup Siap Panen']);
        $karkas = Category::firstOrCreate(['category' => 'Karkas Broiler Segar']);
        $parting = Category::firstOrCreate(['category' => 'Ayam Broiler Parting']);
        $fillet = Category::firstOrCreate(['category' => 'Fillet & Boneless Broiler']);
        $byProduct = Category::firstOrCreate(['category' => 'By-Product & Organ Unggas']);

        $products = [
            [
                'name' => 'Ayam Broiler Hidup Standar (1.6 - 1.8 kg)',
                'slug' => Str::slug('Ayam Broiler Hidup Standar 1.6 - 1.8 kg'),
                'description' => 'Ayam broiler hidup siap panen dengan bobot rata-rata 1.6 - 1.8 kg/ekor. Umur pemeliharaan 32 hari, kondisi sehat prima, berasal dari kandang closed house mitra peternak terverifikasi.',
                'category_id' => $ayamHidup->category_id,
                'price' => 21500,
                'stock' => 4500,
                'img' => 'products/broiler-hidup-standar.jpg',
            ],
            [
                'name' => 'Ayam Broiler Hidup Jumbo (2.2 - 2.5 kg)',
                'slug' => Str::slug('Ayam Broiler Hidup Jumbo 2.2 - 2.5 kg'),
                'description' => 'Ayam broiler hidup ukuran jumbo bobot 2.2 - 2.5 kg/ekor. Sangat cocok untuk kebutuhan katering, rumah makan Padang, dan industri olahan ayam.',
                'category_id' => $ayamHidup->category_id,
                'price' => 19800,
                'stock' => 8500,
                'img' => 'products/broiler-hidup-jumbo.jpg',
            ],
            [
                'name' => 'Ayam Broiler Hidup Ringan (1.2 - 1.4 kg)',
                'slug' => Str::slug('Ayam Broiler Hidup Ringan 1.2 - 1.4 kg'),
                'description' => 'Ayam broiler ukuran kecil (1.2 - 1.4 kg/ekor) dengan daging empuk dan juicy. Ideal untuk menu ayam goreng utuh atau pecel lele/ayam.',
                'category_id' => $ayamHidup->category_id,
                'price' => 22000,
                'stock' => 3800,
                'img' => 'products/broiler-hidup-ringan.jpg',
            ],
            [
                'name' => 'Karkas Broiler Segar Utuh 1.0 - 1.2 kg',
                'slug' => Str::slug('Karkas Broiler Segar Utuh 1.0 - 1.2 kg'),
                'description' => 'Ayam karkas utuh segar ukuran 1.0 - 1.2 kg. Disembelih sesuai syariat halal, bersih dari bulu, tanpa kepala, ceker, dan jeroan. Siap diolah.',
                'category_id' => $karkas->category_id,
                'price' => 34000,
                'stock' => 150,
                'img' => 'products/karkas-segar-1-2kg.jpg',
            ],
            [
                'name' => 'Karkas Broiler Segar Utuh 1.6 - 1.8 kg',
                'slug' => Str::slug('Karkas Broiler Segar Utuh 1.6 - 1.8 kg'),
                'description' => 'Ayam karkas broiler utuh ukuran besar 1.6 - 1.8 kg. Kualitas premium, daging tebal dan padat, cocok untuk olahan panggangan atau restoran.',
                'category_id' => $karkas->category_id,
                'price' => 42000,
                'stock' => 200,
                'img' => 'products/karkas-segar-1-6kg.jpg',
            ],
            [
                'name' => 'Dada Fillet Boneless Skinless (BSF) 1 kg',
                'slug' => Str::slug('Dada Fillet Boneless Skinless BSF 1 kg'),
                'description' => 'Daging dada ayam broiler tanpa tulang dan tanpa kulit (BSF). Rendah lemak, tinggi protein, higienis, dan dikemas rapat per 1 kg.',
                'category_id' => $fillet->category_id,
                'price' => 48500,
                'stock' => 80,
                'img' => 'products/dada-fillet-bsf.jpg',
            ],
            [
                'name' => 'Paha Fillet Boneless Skinless (BL) 1 kg',
                'slug' => Str::slug('Paha Fillet Boneless Skinless BL 1 kg'),
                'description' => 'Daging paha ayam broiler tanpa tulang (BL). Tekstur empuk, juicy, dan kaya rasa, pilihan utama untuk sate taichan, chicken katsu, dan steak.',
                'category_id' => $fillet->category_id,
                'price' => 46500,
                'stock' => 90,
                'img' => 'products/paha-fillet-bl.jpg',
            ],
            [
                'name' => 'Ayam Parting Segar Potong 8 (Pack 1 kg)',
                'slug' => Str::slug('Ayam Parting Segar Potong 8 Pack 1 kg'),
                'description' => 'Karkas ayam broiler segar yang dipotong merata menjadi 8 bagian (2 dada, 2 paha atas, 2 paha bawah, 2 sayap). Praktis untuk masakan keluarga.',
                'category_id' => $parting->category_id,
                'price' => 37000,
                'stock' => 120,
                'img' => 'products/ayam-parting-potong-8.jpg',
            ],
            [
                'name' => 'Sayap Ayam Broiler Segar (Chicken Wings 1 kg)',
                'slug' => Str::slug('Sayap Ayam Broiler Segar Chicken Wings 1 kg'),
                'description' => 'Sayap ayam broiler segar pilihan (utuh drumette dan wingette). Sangat pas untuk menu buffalo wings, spicy wings, atau sup.',
                'category_id' => $byProduct->category_id,
                'price' => 32000,
                'stock' => 75,
                'img' => 'products/sayap-ayam-1kg.jpg',
            ],
            [
                'name' => 'Ceker Ayam Broiler Bersih (Pack 1 kg)',
                'slug' => Str::slug('Ceker Ayam Broiler Bersih Pack 1 kg'),
                'description' => 'Ceker ayam broiler yang telah dikuliti bersih dan dipotong kuku. Mengandung kolagen tinggi, cocok untuk sup ceker, seblak, atau kaldu gurih.',
                'category_id' => $byProduct->category_id,
                'price' => 24000,
                'stock' => 60,
                'img' => 'products/ceker-ayam-1kg.jpg',
            ],
        ];

        foreach ($products as $item) {
            Product::updateOrCreate(
                ['slug' => $item['slug']],
                $item
            );
        }
    }
}
