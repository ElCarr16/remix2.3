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
                '36' => '36',
                '37' => '37',
                '38' => '38',
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
                '28' => '28',
                '30' => '30',
                '32' => '32',
                '34' => '34',
                '36' => '36',
                '38' => '38',
            ],
            'SIZE 9' => [
                '35' => '6',
                '36' => '8',
                '37' => '10',
                '38' => '12',
            ],
            'SIZE 10' => [
                '28' => '5',
                '29' => '7',
                '30' => '9',
            ],
            'SIZE 11' => [
                '33' => 'ML',
                '34' => 'LX',
                '35' => 'XX',
            ],
            'SIZE 12' => [
                '29' => 'ALL SIZE',
            ],
            'SIZE 13' => [
                '24' => 'S',
            ],
            'SIZE 14' => [
                '25' => 'M',
            ],
            'SIZE 15' => [
                '26' => 'L',
            ],
            'SIZE 16' => [
                '27' => 'XL',
            ],
            'SIZE 17' => [
                '28' => '2XL',
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
