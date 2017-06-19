<?php
/**
 * Content.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Repositories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Content
 *
 * 内容模型
 *
 * @property string $title
 * @property string $author_name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Repositories
 */
class Content extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function entity()
    {
        return $this->morphTo('entity', 'entity_type', 'entity_token');
    }
}