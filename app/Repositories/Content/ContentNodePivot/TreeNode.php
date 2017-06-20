<?php
/**
 * TreeNode.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Repositories\Content\ContentNodePivot;


use App\Repositories\Content\Content;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class TreeNode
 *
 * @package App\Repositories\Content\ContentNodePivot
 */
class TreeNode extends Pivot
{
    protected $table = 'content_tree_node_related';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function content()
    {
        return $this->belongsTo(Content::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function entity()
    {
        return $this->morphTo('entity', 'entity_type', 'entity_id');
    }
}