<?php
/**
 * Content.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Schema\Contents\Models;

use App\Schema\User\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Content
 *
 * @property int             $id
 * @property string          $name
 * @property string          $title
 * @property string          $description
 * @property string          $keywords
 * @property Carbon          $created_at
 * @property Carbon          $updated_at
 * @property Carbon          $published_at
 * @property int             $creator_id
 * @property User            $creator
 * @property ContentCategory $category
 * @property Tag[]           $tags
 * @property ContentEntity   $entity
 * @property string          $entity_type
 * @property int             $entity_id
 *
 * @package App\Schema\Contents\Models
 */
class Content extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'title',
        'description',
        'keywords',
        'creator_id',
        'entity_id',
        'entity_type',
    ];

    protected $dates = [
        'published_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function entity()
    {
        return $this->morphTo('entity', 'entity_type', 'entity_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'content_tag_relation');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(ContentCategory::class, 'category_id', 'id');
    }
}