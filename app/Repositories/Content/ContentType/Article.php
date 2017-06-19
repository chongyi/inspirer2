<?php
/**
 * Article.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Repositories\Content\ContentType;

use App\Contracts\ContentStructure;
use App\Repositories\Content\Content;
use App\Repositories\Traits\ContentType\ContentMetaSetterAndGetterTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Article
 *
 * @property int              $id
 * @property ContentStructure $facade
 * @property string           $content
 * @property Carbon           $created_at
 * @property Carbon           $updated_at
 *
 * @package App\Repositories\Content\ContentType
 */
class Article extends Model implements ContentStructure
{
    use SoftDeletes, ContentMetaSetterAndGetterTrait;

    protected $table = 'content_articles';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function facade()
    {
        return $this->morphOne(Content::class, 'entity', 'entity_type', 'entity_id', 'id');
    }
}