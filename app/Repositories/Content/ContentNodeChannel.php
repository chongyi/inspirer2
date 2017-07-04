<?php
/**
 * ContentNodeChannel.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Repositories\Content;

use App\Exceptions\InvalidArgumentException;
use App\Framework\Database\QueryCache;
use Carbon\Carbon;
use App\Framework\Database\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class ContentNodeChannel
 *
 * 内容节点频道
 *
 * @property int               $id
 * @property string            $node_type
 * @property string            $name
 * @property string            $display_name
 * @property string            $description
 * @property Carbon            $created_at
 * @property Carbon            $updated_at
 * @property ContentTreeNode[] $nodes
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function nodes()
    {
        return $this->hasMany(ContentTreeNode::class, 'channel_id', 'id');
    }

    /**
     * 获取当前或指定频道下的根节点列表
     *
     * @param int  $channelId
     * @param bool $cache
     *
     * @return ContentTreeNode[]|Collection
     *
     * @throws InvalidArgumentException
     */
    public function getRootNodesByChannelId($channelId = null, $cache = false)
    {
        if (!$channelId) {
            if ($this->exists) {
                $channelId = $this->id;
            } else {
                throw new InvalidArgumentException();
            }
        }

        return (new QueryCache())->cache($cache)->prefix('A/R/C/CNC')
                                 ->parameters(['channel_id' => $channelId])
                                 ->query(function ($parameters) {
                                     return ContentTreeNode::rootNodes($parameters['channel_id'])->select([
                                         'id',
                                         'title',
                                         'keywords',
                                         'description',
                                         'created_at',
                                         'updated_at'
                                     ])->orderBy('created_at', 'desc')->get();
                                 })->get();
    }
}