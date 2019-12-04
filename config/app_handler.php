<?php
return [
    'app_name' => env('APP_NAME'),
    //
    'allowed_link' => [
        /** All user */
        ['index' => '005', 'type' => 'sidebar', 'auth' => 'all', 'url_name' => 'Home', 'url_link' => '/home', 'url_icon' => 'fas fa-home', 'url_desc' => ''],
        ['index' => '010', 'type' => 'sidebar', 'auth' => 'all', 'url_name' => 'Doc Products', 'url_link' => '/page-products', 'url_icon' => 'fab fa-product-hunt', 'url_desc' => ''],
    ]
];
