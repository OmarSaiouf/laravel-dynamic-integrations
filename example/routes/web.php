<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Omarsaiouf\Integrations\Facades\Integration;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('integrations')->group(function () {
    Route::get('/', function () {
        return response()->json([
            'endpoints' => [
                'GET /integrations/posts?userId=1',
                'GET /integrations/posts/{postId}',
                'POST /integrations/posts',
            ],
        ]);
    });

    Route::get('/posts', function (Request $request) {
        $inputs = [
            'userId' => $request->query('userId', 1),
        ];

        return response()->json(
            Integration::run('jsonplaceholder', 'list_posts', $inputs)
        );
    });

    Route::get('/posts/{postId}', function (int $postId) {
        return response()->json(
            Integration::run('jsonplaceholder', 'get_post', ['postId' => $postId])
        );
    });

    Route::post('/posts', function (Request $request) {
        $inputs = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'body' => ['required', 'string'],
            'userId' => ['required', 'integer'],
        ]);

        return response()->json(
            Integration::run('jsonplaceholder', 'create_post', $inputs)
        );
    });
});
