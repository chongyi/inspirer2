<?php
/**
 * ContentsController.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Http\Controllers\UserArea;

use App\Http\Controllers\Controller;
use App\Repositories\Content\ContentTreeNodeRelated;

/**
 * Class ContentController
 *
 * 内容控制器
 *
 * @package App\Http\Controllers\UserArea
 */
class ContentsController extends Controller
{
    public function index()
    {
        $query = ContentTreeNodeRelated::query();

        // 查询条件
        // ...

        $paginalCollection = $query->with(['node', 'content', 'entity'])->paginate();

        return $paginalCollection;
    }
}