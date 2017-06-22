<?php
/**
 * Article.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Repositories\Content\ContentType;

use App\Contracts\ContentStructure;
use App\Repositories\Content\Attachment;
use App\Repositories\Content\Content;
use App\Repositories\Traits\AttachmentTrait;
use App\Repositories\Traits\ContentEntityTrait;
use App\Repositories\Traits\ContentMetaSetterAndGetterTrait;
use App\Repositories\Traits\ContentMetaTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Article
 *
 * @property int              $id
 * @property ContentStructure $facade
 * @property string           $content
 * @property Attachment       $cover
 * @property string           $origin_source
 * @property Carbon           $created_at
 * @property Carbon           $updated_at
 *
 * @package App\Repositories\Content\ContentType
 */
class Article extends Model implements ContentStructure
{
    use SoftDeletes, ContentMetaSetterAndGetterTrait, ContentMetaTrait, ContentEntityTrait, AttachmentTrait;

    protected $table = 'content_articles';

    protected $fillable = [
        'content',
        'origin_source',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cover()
    {
        return $this->attachment('cover');
    }

}