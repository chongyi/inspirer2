<?php
/**
 * Tag.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Repositories\Content;

use App\Framework\Database\Model;
use Carbon\Carbon;

/**
 * Class Tag
 *
 * @property int       $id
 * @property string    $name
 * @property string    $title
 * @property string    $description
 * @property Carbon    $created_at
 * @property Carbon    $updated_at
 * @property Content[] $contents
 *
 * @package App\Repositories\Content
 */
class Tag extends Model
{
    protected $table = 'content_tags';

    /**
     * @return \App\Framework\Database\Relations\BelongsToMany|\Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function contents()
    {
        return $this->belongsToMany(Content::class, 'content_tag_related', 'tag_id', 'content_id');
    }
}