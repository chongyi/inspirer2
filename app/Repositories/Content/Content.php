<?php
/**
 * Content.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Repositories\Content;

use App\Contracts\Content\ContentStructure;
use App\Events\ContentPublished;
use App\Exceptions\InvalidArgumentException;
use App\Exceptions\OperationRejectedException;
use App\Framework\Database\QueryCache;
use App\Framework\Database\Relations\MorphTo;
use App\Repositories\Traits\ContentClassifyTrait;
use App\Repositories\Traits\ContentMetaSetterAndGetterTrait;
use App\Repositories\Traits\QueryParameterTrait;
use App\Repositories\User;
use Carbon\Carbon;
use App\Framework\Database\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Content
 *
 * 内容模型
 *
 * @property int               $id
 * @property string            $title
 * @property string            $keywords
 * @property string            $description
 * @property Content|Model     $entity
 * @property ContentTreeNode[] $nodes
 * @property int               $entity_id
 * @property string            $entity_type
 * @property Carbon            $created_at
 * @property Carbon            $updated_at
 * @property Carbon            $published_at
 * @property User              $author
 * @property string            $author_name
 * @property int               $author_id
 * @property Comment[]         $comments
 * @property string            $name
 * @property Tag[]             $tags
 *
 * @package App\Repositories\Content
 */
class Content extends Model implements ContentStructure
{
    use SoftDeletes, ContentMetaSetterAndGetterTrait, ContentClassifyTrait, QueryParameterTrait;

    protected $dates = [
        'published_at',
    ];

    /**
     * @return MorphTo
     */
    public function entity()
    {
        $relation = $this->morphTo('entity', 'entity_type', 'entity_id');

        if (isset(static::$processContext['entity'])) {
            return $relation->setColumns(static::$processContext['entity']);
        }

        return $relation;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function nodes()
    {
        $relation = $this->belongsToMany(ContentTreeNode::class, 'content_tree_node_related', 'content_id', 'node_id');

        if (isset(static::$processContext['nodes'])) {
            $relation->setColumns(static::$processContext['nodes']);
        }

        return $relation->withTimestamps()->using(ContentTreeNodeRelated::class);

    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    /**
     * 创建内容
     *
     * @param ContentStructure|Model $entity 内容实体
     * @param string|array|User      $author
     *
     * @return bool
     */
    public function make(ContentStructure $entity, $author = null)
    {
        if (!$entity instanceof Model) {
            throw new InvalidArgumentException();
        }

        $this->title = $entity->getTitle();
        $this->keywords = $entity->getKeywords();
        $this->description = $entity->getDescription();

        if (!is_null($author)) {
            $this->setAuthor($author);
        }

        if (!$entity->exists) {
            $entity->saveOrFail();
        }

        $this->entity()->associate($entity);

        return $this->save();
    }

    /**
     * @param ContentStructure $entity
     * @param null             $author
     *
     * @return bool
     */
    public function rebuild(ContentStructure $entity, $author = null)
    {
        if (!$entity instanceof Model) {
            throw new InvalidArgumentException();
        }

        if (!$entity->exists || $this->entity_id != $entity->getKey()) {
            throw new OperationRejectedException();
        }

        $this->title = $entity->getTitle() ?: $this->title;
        $this->keywords = $entity->getKeywords() ?: $this->keywords;
        $this->description = $entity->getDescription() ?: $this->description;

        if (!is_null($author)) {
            $this->setAuthor($author);
        }

        return $this->save();
    }

    /**
     * 设置内容作者
     *
     * @param User|string|array $author
     *
     * @return $this
     */
    public function setAuthor($author)
    {
        if (is_string($author)) {
            $this->author_name = $author;
        } elseif ($author instanceof User) {
            $this->author()->associate($author);
            $this->author_name = $author->getNickname();
        } elseif (is_array($author)) {
            list($author, $authorName) = $author;
            $this->author()->associate($author);
            $this->author_name = $authorName;
        }

        return $this;
    }

    /**
     * 发布内容
     *
     * @param bool $republish 是否重新发布
     *
     * @return bool
     */
    public function publish($republish = false)
    {
        if (!$this->exists) {
            throw new OperationRejectedException();
        }

        if ($this->published_at && !$republish) {
            return false;
        }

        $this->published_at = Carbon::now();
        $this->save();

        if (isset(static::$dispatcher)) {
            static::$dispatcher->dispatch(new ContentPublished($this, $republish));
        }

        return true;
    }

    /**
     * 获取分页后的内容列表
     *
     * @param array $parameters
     * @param bool  $cache
     *
     * @return mixed
     */
    public function getPaginateList($parameters = [], $cache = false)
    {
        $this->fillPaginateParameter($parameters);
        $this->fillParametersDefaultValue($parameters, ['categories', 'title']);

        return (new QueryCache())->prefix('A/R/C/C')->cache($cache)->parameters($parameters)->query(function (
            array $parameters
        ) {
            $query = static::query();

            if ($categories = $parameters['categories']) {
                $query->whereHas('nodes', function (Builder $query) use ($categories) {
                    $query->whereIn('node_id', $categories);
                });
            }

            if ($title = $parameters['title']) {
                $query->where('title', 'like', "%{$title}%");
            }

            if (isset($parameters['private']) && $parameters['private']) {
                $query->where('author_id', auth()->id());
            }

            $relationContext = ['entity' => ['id', 'cover'], 'nodes' => ['id', 'path', 'title', 'parent_id']];
            return Model::contextContainer($relationContext, function () use ($query) {
                return $paginalCollection = $query->with(['nodes', 'entity'])
                                                  ->orderBy('created_at', 'desc')
                                                  ->orderBy('id', 'desc')
                                                  ->paginate(null,
                                                      ['id', 'title', 'entity_type', 'entity_id', 'created_at']);
            });
        })->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'target_id', 'id');
    }

    /**
     * @return \App\Framework\Database\Relations\BelongsToMany|\Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'content_tag_related', 'content_id', 'tag_id');
    }
}