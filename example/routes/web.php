<?php

use Illuminate\Support\Facades\Route;
use Omarsiouf\Integrations\Facades\Integration;

Route::get('/', function () {

    $t = Integration::run('demo_post', 'list_posts', []);

    return view('welcome');
});
