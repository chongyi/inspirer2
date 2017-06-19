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

    protected $foreignKey = 'node_id';

    protected $relatedKey = 'content_id';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function content()
    {
        return $this->belongsTo(Content::class);
    }
}