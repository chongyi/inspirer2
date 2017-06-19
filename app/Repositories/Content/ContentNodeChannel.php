<?php
/**
 * ContentNodeChannel.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Repositories\Content;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ContentNodeChannel
 *
 * 内容节点频道
 *
 * @package App\Repositories\Content
 */
class ContentNodeChannel extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function treeNodes()
    {
        return $this->hasMany(ContentTreeNode::class, 'channel_id', 'id');
    }
}