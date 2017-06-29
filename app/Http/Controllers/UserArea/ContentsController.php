<?php
/**
 * ContentsController.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Http\Controllers\UserArea;

use App\Contracts\Content\ContentProcessor;
use App\Exceptions\RuntimeException;
use App\Framework\Database\Model;
use App\Http\Controllers\Controller;
use App\Repositories\Content\Content;
use App\Repositories\Regret;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Application;
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
            'categories' => 'array',
            'title'      => 'string',
        ]);

        $query = Content::query();

        if ($categories = $request->input('categories')) {
            $query->whereHas('nodes', function (Builder $query) use ($categories) {
                $query->whereIn('node_id', $categories);
            });
        }

        if ($title = $request->input('title')) {
            $query->where('title', 'like', "%{$title}%");
        }

        $relationContext = ['entity' => ['id', 'cover'], 'nodes' => ['id', 'path', 'title', 'parent_id']];
        $paginalCollection = Model::contextContainer($relationContext, function () use ($query) {
            return $paginalCollection = $query->with(['nodes', 'entity'])
                                              ->orderBy('created_at', 'desc')
                                              ->orderBy('id', 'desc')
                                              ->paginate(null,
                                                  ['id', 'title', 'entity_type', 'entity_id', 'created_at']);
        });


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

    public function store($entity)
    {
        /** @var ContentProcessor $processor */
        $processor = Application::getInstance()->makeWith(ContentProcessor::class, [$entity]);

        return $processor->create();
    }

    public function update($entity, $contentId)
    {
        /** @var ContentProcessor $processor */
        $processor = Application::getInstance()->makeWith(ContentProcessor::class, [$entity]);

        return $processor->update($contentId);
    }

    public function destroy($contentId)
    {
        try {
            Model::resolveConnection()->transaction(function () use ($contentId) {
                /** @var Content $content */
                $content = Content::query()->with('entity')->findOrFail($contentId);
                $entity = $content->entity;
                $treeNodes = $content->nodes;

                foreach ($treeNodes as $node) {
                    (new Regret())->record(sha1(implode(':', [Content::class, $contentId])), $node->pivot->toArray());
                    $node->contents()->detach($content->id);
                }

                if (!$entity->delete() || !$content->delete()) {
                    throw new RuntimeException();
                }
            });

            return [];
        } catch (RuntimeException $e) {
            return [];
        }
    }
}