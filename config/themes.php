<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Shop Theme Configuration
    |--------------------------------------------------------------------------
    |
    | All the configurations are related to the shop themes.
    |
    */

    'shop-default' => 'default',

    'shop' => [
        'default' => [
            'name' => 'Default',
            'assets_path' => 'public/themes/shop/default',
            'views_path' => 'resources/themes/default/views',

            'vite' => [
                'hot_file' => 'shop-default-vite.hot',
                'build_directory' => 'themes/shop/default/build',
                'package_assets_directory' => 'src/Resources/assets',
            ],
        ],

        'visual-debut' => [
            'code' => 'visual-debut',
            'name' => 'Visual Debut',
            'version' => '2.0.0-alpha',
            'author' => 'Bagisto Plus',
            'assets_path' => 'public/themes/shop/visual-debut',
            'views_path' => 'resources/themes/visual-debut/views',
            'preview_image' => 'themes/shop/visual-debut/images/theme-preview.png',
            'documentation_url' => 'https://visual.bagistoplus.com',
            'visual_theme' => true,

            'vite' => [
                'hot_file' => 'themes/shop/visual-debut/visual-debut-vite.hot',
                'build_directory' => 'themes/shop/visual-debut',
                'package_assets_directory' => 'resources/assets',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Theme Configuration
    |--------------------------------------------------------------------------
    |
    | All the configurations are related to the admin themes.
    |
    */

    'admin-default' => 'default',

    'admin' => [
        'default' => [
            'name' => 'Default',
            'assets_path' => 'public/themes/admin/default',
            'views_path' => 'resources/admin-themes/default/views',

            'vite' => [
                'hot_file' => 'admin-default-vite.hot',
                'build_directory' => 'themes/admin/default/build',
                'package_assets_directory' => 'src/Resources/assets',
            ],
        ],
    ],
];
