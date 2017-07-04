<?php
/**
 * Comment.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Repositories\Content;

use App\Framework\Database\Model;
use App\Repositories\User;
use Carbon\Carbon;

/**
 * Class Comment
 *
 * 评论模型
 *
 * @property int       $id
 * @property Content   $target
 * @property Model     $entity
 * @property int       $target_id
 * @property int       $entity_id
 * @property string    $entity_type
 * @property string    $discussant
 * @property string    $discussant_context
 * @property int       $user_id
 * @property User      $user
 * @property int       $parent_id
 * @property Comment   $parent
 * @property Comment[] $children
 * @property string    $content
 * @property Carbon    $created_at
 * @property Carbon    $updated_at
 *
 * @package App\Repositories\Content
 */
class Comment extends Model
{
    protected $table = 'content_comments';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function target()
    {
        return $this->belongsTo(Content::class, 'target_id', 'id');
    }

    /**
     * @return \App\Framework\Database\Relations\MorphTo
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(Comment::class, 'parent_id', 'id');
    }
}