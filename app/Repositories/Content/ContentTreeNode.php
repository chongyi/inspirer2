<?php
/**
 * ContentTreeNode.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Repositories\Content;

use App\Contracts\ContentStructure;
use App\Exceptions\OperationRejectedException;
use App\Repositories\Content\ContentNodePivot\TreeNode;
use App\Repositories\Traits\ContentMetaSetterAndGetterTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

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
 * @property Carbon             $created_at
 * @property Carbon             $updated_at
 * @property Content[]          $contents
 * @property string             $path
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
        return $this->belongsToMany(Content::class, 'content_tree_node_related', 'node_id',
            'content_id')->using(TreeNode::class);
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

        $parents = explode(',', $this->path);

        if ($parents === false) {
            return $this;
        }

        $parents[] = $this->id;
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
            /** @var TreeNode $treeNode */
            $treeNode = $this->contents()->newPivot();

            $treeNode->content()->associate($content);
            $treeNode->entity()->associate($content->entity);
            $treeNode->node()->associate($this);

            return $treeNode->save();
        }

        throw new OperationRejectedException();
    }
}