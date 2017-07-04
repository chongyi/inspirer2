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

        return (new Content())->getPaginateList([
            'categories' => $request->input('categories'),
            'title'      => $request->input('title'),
            'private'    => true,
        ], true);
    }

    public function show(Request $request, $id)
    {
        if (!$request->ajax()) {
            return view('welcome');
        }

        $data = Content::query()->where('author_id', auth()->id())->with('nodes', 'entity')->findOrFail($id);

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
                $content = Content::query()->where('author_id', auth()->id())->with('entity')->findOrFail($contentId);
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