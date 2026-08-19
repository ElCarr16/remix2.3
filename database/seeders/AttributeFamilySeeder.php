<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Webkul\Attribute\Models\Attribute;
use Webkul\Attribute\Models\AttributeFamily;
use Webkul\Attribute\Models\AttributeGroup;

class AttributeFamilySeeder extends Seeder
{
    public function run()
    {
        // 1. Ambil atribut wajib bawaan Bagisto dari family 'default' (kecuali 'size')
        $defaultFamily = AttributeFamily::where('code', 'default')->first();
        $defaultAttributes = $defaultFamily ? $defaultFamily->custom_attributes()->where('attributes.code', '!=', 'size')->get() : collect();

        // 2. Lakukan perulangan untuk SIZE 1 sampai SIZE 17
        for ($i = 1; $i <= 17; $i++) {

            $sizeAttribute = Attribute::where('code', "size_{$i}")->first();

            if ($sizeAttribute) {

                // A. Buat Attribute Family (Jika sudah ada, abaikan)
                $family = AttributeFamily::firstOrCreate(
                    ['code' => "family_size_{$i}"],
                    ['name' => "Family SIZE {$i}", 'status' => 1, 'is_user_defined' => 1]
                );

                // B. Buat atau Perbarui Grup "General"
                // PERBAIKAN: Kita cari berdasarkan 'name', jika sudah ada (bekas gagal tadi),
                // sistem tidak akan membuat baru, melainkan hanya menimpanya (update).
                $group = AttributeGroup::updateOrCreate(
                    ['attribute_family_id' => $family->id, 'name' => 'General'],
                    ['code' => 'general', 'position' => 1, 'is_user_defined' => 0]
                );

                // C. Siapkan data posisi atribut
                $syncData = [];
                $syncData[$sizeAttribute->id] = ['position' => 1];

                foreach ($defaultAttributes as $index => $attr) {
                    $syncData[$attr->id] = ['position' => $index + 2];
                }

                // D. Sinkronisasi ke database tanpa memicu error duplikat
                $group->custom_attributes()->syncWithoutDetaching($syncData);

                $this->command->info("Family SIZE {$i} berhasil dirangkai!");
            }
        }

        $this->command->info("Semua Attribute Family Selesai Dibuat!");
    }
}
