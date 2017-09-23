<?php
/**
 * Content.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Schema\Contents\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Content
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $keywords
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property ContentCategory $category
 *
 * @package App\Schema\Contents\Models
 */
class Content extends Model
{
    use SoftDeletes;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(ContentCategory::class, 'category_id', 'id');
    }
}