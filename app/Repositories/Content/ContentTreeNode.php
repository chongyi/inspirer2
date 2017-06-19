<?php
/**
 * ContentTreeNode.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Repositories\Content;

use App\Contracts\ContentStructure;
use App\Repositories\Content\ContentNodePivot\TreeNode;
use App\Repositories\Traits\ContentType\ContentMetaSetterAndGetterTrait;
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
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function channel()
    {
        return $this->morphOne(ContentNodeChannel::class, 'node', 'node_type', 'node_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function contents()
    {
        return $this->belongsToMany(Content::class)->using(TreeNode::class);
    }
}