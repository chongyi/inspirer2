<?php
/**
 * ContentTreeNode.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Repositories\Content;

use App\Contracts\Content\ContentStructure;
use App\Exceptions\OperationRejectedException;
use App\Repositories\Traits\ContentMetaSetterAndGetterTrait;
use Carbon\Carbon;
use App\Framework\Database\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class ContentTreeNode
 *
 * 内容树状节点
 *
 * @property int                $id
 * @property string             $title
 * @property string             $keywords
 * @property string             $description
 * @property ContentTreeNode    $parent
 * @property ContentTreeNode[]  $children
 * @property ContentNodeChannel $channel
 * @property int                $channel_id
 * @property Carbon             $created_at
 * @property Carbon             $updated_at
 * @property Content[]          $contents
 * @property string             $path
 * @property string             $name
 *
 * @method static Builder rootNodes(int $channelId = null)
 *
 * @package App\Repositories\Content
 */
class ContentTreeNode extends Model implements ContentStructure
{
    use ContentMetaSetterAndGetterTrait;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(static::class, 'parent_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(static::class, 'parent_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function channel()
    {
        return $this->belongsTo(ContentNodeChannel::class, 'channel_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function contents()
    {
        return $this->belongsToMany(Content::class, 'content_tree_node_related', 'node_id', 'content_id')
                    ->withTimestamps()
                    ->using(ContentTreeNodeRelated::class);
    }

    /**
     * 添加子节点
     *
     * @param ContentTreeNode $node
     *
     * @return $this
     */
    public function addChild(ContentTreeNode $node)
    {
        if (!$this->exists) {
            throw new OperationRejectedException();
        }

        if (!$node->exists) {
            $node->channel_id = $this->channel_id;
            $node->parent()->associate($this);
            $node->save();
        }

        $parents = explode(',', $this->path);

        $parents[] = $node->id;
        $node->path = implode(',', $parents);
        $node->save();

        return $this;
    }

    /**
     * 添加内容至该节点
     *
     * @param Content $content
     *
     * @return bool
     */
    public function addContent(Content $content)
    {
        if ($this->exists) {
            $this->contents()
                 ->save($content, [
                     'entity_id'   => $content->entity_id,
                     'entity_type' => $content->entity_type,
                 ]);

            return true;
        }

        throw new OperationRejectedException();
    }

    /**
     * 获取节点下（包括子节点）的所有内容的查询对象
     *
     * @param \Closure|null $callback 针对内容的查询回调
     *
     * @return Builder
     */
    public function allContentsQuery(\Closure $callback = null)
    {
        // 获取子节点
        $nodeIds = static::query()->where('path', 'like', "{$this->path}%")->pluck('id')->all();
        $query = Content::query()->whereHas('nodes', function (Builder $query) use ($nodeIds) {
            $query->whereIn('node_id', $nodeIds);
        });

        if (!is_null($callback)) {
            $callback($query);
        }

        return $query;
    }

    /**
     * @param Builder $query
     * @param null    $channelId
     *
     * @return Builder
     */
    public function scopeRootNodes(Builder $query, $channelId = null)
    {
        if ($channelId) {
            $query->where('channel_id', $channelId);
        }

        return $query->where('parent_id', 0);
    }
}