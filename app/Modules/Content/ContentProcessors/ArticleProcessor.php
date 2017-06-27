<?php
/**
 * ArticleProcessor.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Modules\Content\ContentProcessors;

use App\Contracts\Content\ContentProcessor;
use App\Exceptions\OperationRejectedException;
use App\Framework\Database\Model;
use App\Repositories\Content\Attachment;
use App\Repositories\Content\ContentEntity\Article;
use App\Repositories\Content\ContentTreeNode;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use App\Repositories\Content\Content;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Ramsey\Uuid\Uuid;

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
            'cover'         => 'nullable|attachment:image',
            'category_id'   => [
                Rule::exists('content_tree_nodes', 'id')->where(function (\Illuminate\Database\Query\Builder $query) {
                    $query->where('channel_id', 1);
                }),
            ],
        ]);

        return Model::resolveConnection()->transaction(function () {
            $entity = new Article();
            $this->entityBuild($entity);

            ($content = new Content())->make($entity, auth()->user());
            $this->categoryBinder($content);

            if ($this->request->input('publish')) {
                $content->publish();
            }

            return $content;
        });
    }

    public function update($contentId)
    {
        $this->validate($this->request, [
            'title'         => 'required|string|between:1,255',
            'keywords'      => 'nullable|string|max:255',
            'description'   => 'nullable|string',
            'content'       => 'required',
            'origin_source' => 'nullable|url',
            'publish'       => 'nullable|boolean',
            'cover'         => 'nullable|attachment:image',
            'category_id'   => [
                Rule::exists('content_tree_nodes', 'id')->where(function (\Illuminate\Database\Query\Builder $query) {
                    $query->where('channel_id', 1);
                }),
            ],
        ]);

        return Model::resolveConnection()->transaction(function () use ($contentId) {
            /** @var Content $content */
            $content = Content::query()->where('id', $contentId)->with('entity')->firstOrFail();
            $entity = $content->entity;
            if (!$entity instanceof Article) {
                throw new OperationRejectedException();
            }

            $this->entityBuild($entity);
            $content->rebuild($entity);

            if ($this->request->input('publish')) {
                $content->publish();
            }

            return $content;
        });
    }

    private function entityBuild(Article $entity)
    {
        $entity->setTitle($this->request->input('title'))
               ->setKeywords($this->request->input('keywords'))
               ->setDescription($this->request->input('description'))
               ->setContent($this->request->input('content'))
               ->setOriginSource($this->request->input('origin_source'));

        $cover = $this->request->file('cover');
        if (!is_null($cover) && $cover->isValid()) {
            $path = config('app.storage.content.article_cover');
            $fullPath = $path . '/' . Uuid::uuid4()->toString() . '.' . $cover->getExtension();
            $attachment = (new Attachment())->upload($fullPath, $cover);

            $entity->setCover($attachment->token);
        } elseif ($cover = $this->request->input('cover')) {
            $entity->setCover($cover);
        }
    }

    private function categoryBinder(Content $content)
    {
        if ($categoryId = $this->request->input('category_id')) {
            /** @var ContentTreeNode $category */
            $category = ContentTreeNode::query()->whereHas('channel', function (Builder $query) {
                $query->where('name', 'category');
            })->where('id', $categoryId)->firstOrFail();

            $category->addContent($content);
        }
    }
}