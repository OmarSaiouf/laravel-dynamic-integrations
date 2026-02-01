<?php

use Illuminate\Support\Facades\Route;
use Omarsaiouf\Integrations\Facades\Integration;

Route::get('/', function () {

    $t = Integration::run('demo_post', 'list_posts', []);
    dd($t);
    return view('welcome');
});
