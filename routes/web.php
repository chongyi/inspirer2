<?php

use App\Http\Controllers;
use App\Http\Controllers\Blog;
use Illuminate\Routing\Router;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


/** @var Router $router */
$router->get('/', Controllers\HomeController::class . '@index')->name('w:home.index');

$router->group(['prefix' => 'blog'], function (Router $router) {
    $router->get('{type}/{find}', Blog\MainController::class . '@content')
           ->where('type', '\w+')
           ->name('w:blog.content');
});
