<?php

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

use Illuminate\Routing\Router;
use App\Http\Controllers\{
    UserArea
};

/**
 * @var Router $router
 */

$router->post('private/login', UserArea\AuthController::class . '@loginRequest')->name('private.login');
$router->post('private/logout', UserArea\AuthController::class . '@logoutRequest')->name('private.logout');

$router->group(['prefix' => 'private', 'middleware' => ['auth']], function (Router $router) {
    $router->get('content', UserArea\ContentsController::class . '@index')->name('private.content.index');
    $router->get('content/{id}', UserArea\ContentsController::class . '@show')->name('private.content.show');
    $router->post('content/{type}', UserArea\ContentsController::class . '@store')->name('private.user.content.store');
    $router->delete('content/{id}', UserArea\ContentsController::class . '@destroy')->name('private.content.destroy');
    $router->match(['put', 'patch'], 'content/{type}/{id}', UserArea\ContentsController::class . '@update')
           ->name('private.content.update');

    $router->get('category', UserArea\CategoriesController::class . '@index')->name('private.category.index');
    $router->get('tag', UserArea\TagsController::class . '@index')->name('private.tag.index');
});