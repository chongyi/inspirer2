<?php
/**
 * Article.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Repositories\ContentType;

use App\Contracts\ContentEntity;
use App\Repositories\Content;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Article
 *
 * @property int     $id
 * @property Content $facade
 * @property string  $content
 * @property Carbon  $created_at
 * @property Carbon  $updated_at
 *
 * @package App\Repositories\ContentType
 */
class Article extends Model implements ContentEntity
{
    use SoftDeletes, ContentMetaTrait;

    protected $table = 'content_articles';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function facade()
    {
        return $this->morphOne(Content::class, 'entity', 'entity_type', 'entity_id', 'id');
    }
}