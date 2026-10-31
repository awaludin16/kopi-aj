<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            [
                'category_id' => 1,
                'name' => 'Rice Bowl Ayam Tiren (Mati Keren)',
                'description' => 'Nasi ayam dengan bumbu khas. Posisi ayam tetap keren saat disembelih, makanya dinamakan tiren. Disembelih dengan doa 5 agama',
                'price' => 17500,
                'stock' => 100,
                'image' => 'rice-bowl-ayam-tiren.jpg',
            ],
            [
                'category_id' => 1,
                'name' => 'Rice Bowl Jamur Punya Giant',
                'description' => 'Nasi dengan jamur mix, Namanya aja yang buat Giant, tapi aslinya buat Suneo. Tapi buat Nobita udah kenyang sih, awikwok',
                'price' => 19000,
                'stock' => 100,
                'image' => 'rice-bowl-jamur-punya-giant.jpg',
            ],
            [
                'category_id' => 1,
                'name' => 'Rice Bowl Sapi Metal',
                'description' => 'Nasi sapi biasa sih, bukan sapi metal, tapi enak. Kata supplier sapinya, daging diambil dari sapi terpilih di zaman dinasti kuno',
                'price' => 20250,
                'stock' => 100,
                'image' => 'rice-bowl-sapi-metal.jpeg',
            ],
            [
                'category_id' => 1,
                'name' => 'Rice Bowl Dori teman nya Nemo',
                'description' => 'Nasi Dori filet tanpa tulang, tikenal sebgai ransum penambah stamina TNI Jepang pada perang dunia kedua saat melawan Marine Ford',
                'price' => 20550,
                'stock' => 100,
                'image' => 'rice-bowl-dori-teman-nya-nemo.png',
            ],
            [
                'category_id' => 2,
                'name' => 'Mie Jebew Batok Supra',
                'description' => 'Mie pedas yang langsung nyerang dari suapan pertama. Rasanya nendang? angin?, pedasnya pedas parah, pelan-pelan bikin keringetan tanpa disuruh',
                'price' => 14500,
                'stock' => 100,
                'image' => 'mie-jebew.jpg',
            ],
            [
                'category_id' => 2,
                'name' => 'Nugget Mie Alias Midog',
                'description' => 'Mie yang dibikin beda karena dibungkus nugget renyah. Midog jir Emih Endog, gaada nuget-nugetnya ygy',
                'price' => 13250,
                'stock' => 100,
                'image' => 'nuget-mie.jpg',
            ],
            [
                'category_id' => 2,
                'name' => 'Mie Nyemek hmm',
                'description' => 'Mie setengah basah dengan rasa yang lebih kalem tapi tetap nagih. Nggak terlalu berkuah, nggak juga kering, teksturnya pas dan bikin nyaman. Pilihan enak kalau lagi pengen mie yang anget tapi nggak berat',
                'price' => 14000,
                'stock' => 100,
                'image' => 'mie-nyemek.jpg',
            ],
            [
                'category_id' => 2,
                'name' => 'TikTok Ramen Dinasti Ming',
                'description' => 'Ramen simpel dengan rasa yang langsung nyantol. Butter bikin creamy, garlic bikin wangi, dan semuanya nyatu dengan santai. Cocok buat yang pengen coba menu viral tapi tetap pengen makan enak tanpa ribet',
                'price' => 14500,
                'stock' => 100,
                'image' => 'tiktok-ramen.jpg',
            ],
            [
                'category_id' => 3,
                'name' => 'Mineral Woah',
                'description' => 'Air putih biasa, berharap apa? bisa nyembuhin penyakit? tch dipikir air celupan jari tangan si fulan',
                'price' => 7000,
                'stock' => 100,
                'image' => 'mineral-water.jpg',
            ],
            [
                'category_id' => 3,
                'name' => 'Iced Americano',
                'description' => 'Kopi hitam dingin yang rasanya clean dan to the point. Nggak manis, nggak neko-neko, tapi nyegerin dan bikin melek. Cocok buat yang suka kopi apa adanya dan pengen fokus tanpa drama',
                'price' => 10000,
                'stock' => 100,
                'image' => 'iced-americano.png',
            ],
            [
                'category_id' => 3,
                'name' => 'Lemon Tea',
                'description' => 'Perpaduan teh dan lemon yang ringan dan nyegerin. Rasanya asam manis seimbang, enak diminum pas siang hari atau pas lagi pengen minum yang nggak berat tapi tetap berasa',
                'price' => 8000,
                'stock' => 100,
                'image' => 'lemon-tea.jpg',
            ],
            [
                'category_id' => 3,
                'name' => 'Thai Tea',
                'description' => 'Minuman manis creamy dengan aroma khas yang langsung kerasa. Rasanya bold tapi tetap santai, cocok buat yang pengen minuman manis yang bikin mood naik tanpa mikir panjang',
                'price' => 10000,
                'stock' => 100,
                'image' => 'thai-tea.jpg',
            ],
            [
                'category_id' => 3,
                'name' => 'Matcha Latte',
                'description' => 'Matcha yang lembut ketemu susu creamy, rasanya tenang tapi nagih. Nggak terlalu pahit, nggak terlalu manis, pas buat nemenin nongkrong lama atau kerja santai',
                'price' => 12000,
                'stock' => 100,
                'image' => 'matcha-latte.png',
            ],
            [
                'category_id' => 3,
                'name' => 'Juk Jerus',
                'description' => 'Jeruk segar yang rasanya ringan dan nyegerin. Asamnya pas, manisnya alami, cocok diminum kapan aja buat ngilangin haus tanpa rasa berat',
                'price' => 8000,
                'stock' => 100,
                'image' => 'juk-jerus.jpeg',
            ],
            [
                'category_id' => 4,
                'name' => 'French Fries',
                'description' => 'Kentang goreng, udah ah males ngetik',
                'price' => 12000,
                'stock' => 100,
                'image' => 'french-fries.jpg',
            ],
            [
                'category_id' => 4,
                'name' => 'Ngotak-Ngotak',
                'description' => 'Otak-otak hangat dengan tekstur lembut dan rasa gurih yang enak dimakan pelan-pelan. Cocok buat camilan pembuka sebelum lanjut ke menu lain atau sekadar teman ngobrol',
                'price' => 12000,
                'stock' => 100,
                'image' => 'ngotak-ngotak.jpg',
            ],
            [
                'category_id' => 4,
                'name' => 'Sosis Bangkar',
                'description' => 'Sosis dibakar sampai aromanya keluar dan rasanya makin gurih. Simpel, familiar, dan selalu aman buat nemenin nongkrong tanpa mikir ribet',
                'price' => 12000,
                'stock' => 100,
                'image' => 'sosis-bakar.jpg',
            ],
            [
                'category_id' => 4,
                'name' => 'Potato Wedges',
                'description' => 'Kentang potong tebal yang digoreng garing di luar dan empuk di dalam. Rasanya gurih dan ngenyangin, pas buat dimakan rame-rame atau sendirian juga tetap masuk',
                'price' => 12000,
                'stock' => 100,
                'image' => 'potato-wedges.jpg',
            ],
            [
                'category_id' => 4,
                'name' => 'Onion Ring',
                'description' => 'Irisan bawang dibalut tepung renyah dengan rasa gurih yang ringan. Sekali mulai susah berhenti, cocok buat yang pengen camilan kriuk tapi nggak berat',
                'price' => 12000,
                'stock' => 100,
                'image' => 'onion-ring.jpg',
            ],
            [
                'category_id' => 4,
                'name' => 'Tahu Cabai Garam',
                'description' => 'Tahu goreng garing dengan taburan cabai dan bawang yang wangi. Gurih, sedikit pedas, dan bikin nagih tanpa harus berlebihan',
                'price' => 12000,
                'stock' => 100,
                'image' => 'tahu-cabai-garam.jpg',
            ],
            [
                'category_id' => 4,
                'name' => 'Pisang Goreng',
                'description' => 'Pisang digoreng hangat dengan rasa manis alami dan tekstur lembut di dalam. Camilan klasik yang selalu cocok buat suasana santai',
                'price' => 12000,
                'stock' => 100,
                'image' => 'pisang-goreng.jpg',
            ],
            [
                'category_id' => 4,
                'name' => 'Roti Bakar Coklat Keju',
                'description' => 'Roti bakar dengan coklat manis dan keju gurih yang meleleh. Rasanya seimbang, ngenyangin, dan pas buat penutup sambil duduk lama',
                'price' => 12000,
                'stock' => 100,
                'image' => 'roti-bakar-coklat-keju.jpg',
            ],
        ];

        foreach ($menus as $menu) {
            Menu::create($menu);
        }
    }
}
