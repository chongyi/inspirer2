<?php
/**
 * ContentsController.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Http\Controllers\UserArea;

use App\Http\Controllers\Controller;
use App\Repositories\Content\Content;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Class ContentController
 *
 * 内容控制器
 *
 * @package App\Http\Controllers\UserArea
 */
class ContentsController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) {
            return view('welcome');
        }

        // validate
        $this->validate($request, [
            'categories' => 'array'
        ]);

        $query = Content::query();

        if ($categories = $request->input('categories')) {
            $query->whereHas('nodes', function (Builder $query) use ($categories) {
                $query->whereIn('node_id', $categories);
            });
        }

        $paginalCollection = $query->with(['nodes'])
                                   ->orderBy('created_at', 'desc')
                                   ->orderBy('id', 'desc')
                                   ->paginate();

        return $paginalCollection;
    }

    public function show(Request $request, $id)
    {
        if (!$request->ajax()) {
            return view('welcome');
        }

        $data = Content::query()->with('nodes', 'entity')->findOrFail($id);

        return $data;
    }
}