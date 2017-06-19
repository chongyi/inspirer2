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
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function nodes()
    {
        return $this->morphTo('node', 'node_type', 'node_id');
    }
}