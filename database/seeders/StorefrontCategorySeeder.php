<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Webkul\Category\Repositories\CategoryRepository;
use Illuminate\Support\Facades\DB;

class StorefrontCategorySeeder extends Seeder
{
    /**
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * Create a new seeder instance.
     *
     * @param  CategoryRepository  $categoryRepository
     * @return void
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            'MANS' => [
                'T-SHIRT',
                'KEMEJA',
                'CELANA',
                'JEANS',
                'JACKET/OUTER'
            ],
            'LADIES' => [
                'BLOUSE',
                'KEMEJA',
                'GAMIS',
                'JAKET/OUTER',
                'T-SHIRT',
                'ROK',
                'KULOT',
                'CELANA',
                'JEANS',
                'DRESS/LONG DRESS',
                'GAMIS CASUAL'
            ],
            'BOYS' => [
                'T-SHIRT',
                'KOKO',
                'CELANA'
            ],
            'GIRLS' => [
                'DRESS/LONG DRESS',
                'GAMIS CASUAL',
                'BOLERO',
                'JACKET/OUTER'
            ],
            'FORMAL' => [
                'KEMEJA',
                'KOKO',
                'CELANA'
            ],
            'ACCESORIES' => [
                'TOPI',
                'TAS',
                'SABUK'
            ]
        ];

        // Ensure we know the root category id
        $rootCategory = $this->categoryRepository->where('parent_id', null)->first();
        if (!$rootCategory) {
            $this->command->error('Root category not found!');
            return;
        }

        $locales = core()->getAllLocales()->pluck('code')->toArray();
        if (empty($locales)) {
            $locales = [config('app.locale', 'en')];
        }

        $position = 1;
        foreach ($categories as $parentName => $children) {
            $parentData = [
                'position'     => $position++,
                'status'       => 1,
                'parent_id'    => $rootCategory->id,
                'display_mode' => 'products_and_description',
            ];

            foreach ($locales as $locale) {
                $parentData[$locale] = [
                    'name'             => $parentName,
                    'slug'             => Str::slug($parentName),
                    'description'      => $parentName . ' Category',
                    'meta_title'       => $parentName,
                    'meta_description' => '',
                    'meta_keywords'    => '',
                ];
            }

            // Create parent category
            $parentCategory = $this->categoryRepository->create($parentData);
            $this->command->info("Created Parent Category: {$parentName}");

            $childPosition = 1;
            foreach ($children as $childName) {
                $childData = [
                    'position'     => $childPosition++,
                    'status'       => 1,
                    'parent_id'    => $parentCategory->id,
                    'display_mode' => 'products_and_description',
                ];

                foreach ($locales as $locale) {
                    $slugName = str_replace('/', '-', $childName);
                    $childData[$locale] = [
                        'name'             => $childName,
                        'slug'             => Str::slug($parentName . '-' . $slugName),
                        'description'      => $childName . ' Category',
                        'meta_title'       => $childName,
                        'meta_description' => '',
                        'meta_keywords'    => '',
                    ];
                }

                // Create child category
                $this->categoryRepository->create($childData);
                $this->command->info(" - Created Child Category: {$childName}");
            }
        }
    }
}
