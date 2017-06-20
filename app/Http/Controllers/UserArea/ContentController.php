<?php
/**
 * ContentController.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Http\Controllers\UserArea;

use App\Http\Controllers\Controller;
use App\Repositories\Content\ContentNodePivot\TreeNode;

/**
 * Class ContentController
 *
 * 内容控制器
 *
 * @package App\Http\Controllers\UserArea
 */
class ContentController extends Controller
{
    public function index()
    {
        $query = TreeNode::query();

        // 查询条件
        // ...

        $paginalCollection = $query->with(['node', 'content', 'entity'])->paginate();
    }
}