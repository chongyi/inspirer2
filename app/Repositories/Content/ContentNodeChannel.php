<?php
/**
 * ContentNodeChannel.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Repositories\Content;

use Carbon\Carbon;
use App\Framework\Database\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class ContentNodeChannel
 *
 * 内容节点频道
 *
 * @property string $node_type
 * @property string $name
 * @property string $display_name
 * @property string $description
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @method static ContentNodeChannel findByName(string $name)
 *
 * @package App\Repositories\Content
 */
class ContentNodeChannel extends Model
{
    /**
     * @param Builder $query
     * @param         $name
     *
     * @return mixed
     */
    public function scopeFindByName(Builder $query, $name)
    {
        return $query->where('name', $name)->first();
    }
}