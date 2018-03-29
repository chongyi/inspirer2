<?php
/**
 * Entity.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Models\Contents;

use App\Models\Content;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * 实体
 *
 * @property int     $content_id
 * @property Content $content
 * @property string  $body
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models\Contents
 */
abstract class Entity extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function content()
    {
        return $this->belongsTo(Content::class, 'content_id', 'id');
    }

    public static function find(string $find)
    {

    }
}