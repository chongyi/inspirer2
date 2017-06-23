<?php
/**
 * ArticleProcessor.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Modules\Content\ContentProcessors;

use App\Contracts\Content\ContentProcessor;
use App\Framework\Database\Model;
use App\Repositories\Content\ContentEntity\Article;
use App\Repositories\Content\ContentTreeNode;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use App\Repositories\Content\Content;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ArticleProcessor implements ContentProcessor
{
    use ValidatesRequests;

    /**
     * @var Request
     */
    private $request;

    /**
     * ArticleProcessor constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return Content
     */
    public function create()
    {
        $this->validate($this->request, [
            'title'         => 'required|string|between:1,255',
            'keywords'      => 'nullable|string|max:255',
            'description'   => 'nullable|string',
            'content'       => 'required',
            'origin_source' => 'nullable|url',
            'publish'       => 'nullable|boolean',
            'category_id'   => [
                Rule::exists('content_tree_nodes', 'id')->where(function ($query) {
                    $query->where('channel_id', 1);
                }),
            ],
        ]);

        return Model::resolveConnection()->transaction(function () {
            $entity = (new Article())->setTitle($this->request->input('title'))
                                     ->setKeywords($this->request->input('keywords'))
                                     ->setDescription($this->request->input('description'))
                                     ->setContent($this->request->input('content'))
                                     ->setOriginSource($this->request->input('origin_source'));

            ($content = new Content())->make($entity, auth()->user());

            if ($categoryId = $this->request->input('category_id')) {
                /** @var ContentTreeNode $category */
                $category = ContentTreeNode::query()->whereHas('channel', function ($query) {
                    $query->where('name', 'category');
                })->where('id', $categoryId)->firstOrFail();

                $category->addContent($content);
            }

            if ($this->request->input('publish')) {
                $content->publish();
            }

            return $content;
        });
    }


}