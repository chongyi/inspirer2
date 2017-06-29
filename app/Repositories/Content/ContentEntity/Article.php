<?php
/**
 * Article.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Repositories\Content\ContentEntity;

use App\Contracts\Content\ContentStructure;
use App\Repositories\Content\Attachment;
use App\Repositories\Traits\AttachmentTrait;
use App\Repositories\Traits\ContentEntityTrait;
use App\Repositories\Traits\ContentMetaSetterAndGetterTrait;
use App\Repositories\Traits\ContentMetaTrait;
use Carbon\Carbon;
use App\Framework\Database\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Article
 *
 * @property int              $id
 * @property ContentStructure $facade
 * @property string           $content
 * @property Attachment       $cover_attachment
 * @property string           $cover
 * @property string           $origin_source
 * @property Carbon           $created_at
 * @property Carbon           $updated_at
 *
 * @package App\Repositories\Content\ContentEntity
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
    public function coverAttachment()
    {
        return $this->attachment('cover');
    }

    /**
     * 设置内容
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return Article
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return string
     */
    public function getOriginSource()
    {
        return $this->origin_source;
    }

    /**
     * @param string $origin_source
     *
     * @return Article
     */
    public function setOriginSource($origin_source)
    {
        $this->origin_source = $origin_source;
        return $this;
    }

    /**
     * 设置封面
     *
     * @param string $cover 封面图片附件 token
     *
     * @return Article
     */
    public function setCover($cover)
    {
        $this->cover = $cover;
        return $this;
    }

}