<?php
/**
 * TagsController.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Http\Controllers\UserArea;


use App\Framework\Database\QueryCache;
use App\Http\Controllers\Controller;
use App\Repositories\Content\Tag;

class TagsController extends Controller
{
    public function index()
    {
        return (new QueryCache())->prefix('A/R/C/T')->cache(true)->parameters([])->query(function () {
            return Tag::query()->get(['id', 'title', 'name', 'created_at']);
        })->get();
    }
}