<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Webkul\Attribute\Models\Attribute;
use Webkul\Attribute\Repositories\AttributeOptionRepository;

class SizeChartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Definisikan Pemetaan Ukuran (Lengkapi SIZE 4 - SIZE 17 di sini)
        $sizeGroups = [
            'SIZE 1' => [
                '24' => 'S',
                '25' => 'M',
                '26' => 'L',
                '27' => 'XL',
            ],
            'SIZE 2' => [
                '25' => 'M',
                '26' => 'L',
                '27' => 'XL',
                '28' => '2X',
            ],
            'SIZE 3' => [
                '28' => '2X',
                '29' => '3X',
                '30' => '4X',
            ],

            'SIZE 4' => [
                '28' => '28',
                '29' => '29',
                '30' => '30',
                '31' => '31',
                '32' => '32',
            ],


            'SIZE 5' => [
                '33' => '33',
                '34' => '34',
                '35' => '35',
                '37' => '36',
                '38' => '37',
            ],


            'SIZE 6' => [
                '28' => '2X',
                '29' => '3X',
            ],


            'SIZE 7' => [
                '28' => '28',
                '30' => '30',
                '32' => '32',
                '34' => '34',
                '36' => '36',
                '38' => '38',
            ],

            'SIZE 8' => [
                '35' => '06',
                '36' => '08',
                '37' => '10',
                '38' => '12',
            ],

            'SIZE 9' => [
                '28' => '05',
                '29' => '07',
                '30' => '09',
            ],


            'SIZE 10' => [
                '33' => 'ML',
                '34' => 'LX',
                '35' => 'XX',
            ],

            'SIZE 11' => [
                '29' => 'ALL SIZE',
            ],

            'SIZE 12' => [
                '24' => 'S',
            ],

            'SIZE 13' => [
                '25' => 'M',
            ],
            'SIZE 14' => [
                '26' => 'L',
            ],
            'SIZE 15' => [
                '27' => 'XL',
            ],
            'SIZE 16' => [
                '30' => '2X',
            ],
        ];

        $optionRepo = app(AttributeOptionRepository::class);

        // 2. Proses Pembuatan Atribut dan Opsi
        foreach ($sizeGroups as $groupName => $options) {
            $code = strtolower(str_replace(' ', '_', $groupName)); // Hasil: size_1, size_2, dst.

            // Cek apakah atribut sudah ada agar tidak duplikat
            $existingAttribute = Attribute::where('code', $code)->first();

            if (!$existingAttribute) {
                // Buat Atribut Baru
                $attribute = Attribute::create([
                    'code'                => $code,
                    'admin_name'          => $groupName,
                    'type'                => 'select',
                    'is_required'         => 0,
                    'is_unique'           => 0,
                    'value_per_locale'    => 0,
                    'value_per_channel'   => 0,
                    'is_filterable'       => 1,
                    'is_configurable'     => 1, // Penting agar bisa jadi varian
                    'is_user_defined'     => 1,
                    'is_visible_on_front' => 1,
                ]);

                // Buat Opsi untuk Atribut Tersebut
                $sort = 1;
                foreach ($options as $adminSize => $userDisplaySize) {
                    $optionRepo->create([
                        'attribute_id' => $attribute->id,
                        'admin_name'   => (string) $adminSize,
                        'sort_order'   => $sort++,
                        'en'           => ['label' => $userDisplaySize],
                    ]);
                }
                $this->command->info("Atribut {$groupName} berhasil dibuat.");
            } else {
                $this->command->warn("Atribut {$groupName} sudah ada, dilewati.");
            }
        }

        $this->command->info('Semua Size Chart berhasil di-generate!');
    }
}
