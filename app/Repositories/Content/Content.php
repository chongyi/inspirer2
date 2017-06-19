<?php
/**
 * Content.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Repositories\Content;

use App\Contracts\ContentStructure;
use App\Exceptions\InvalidArgumentException;
use App\Repositories\Traits\ContentType\ContentMetaSetterAndGetterTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Content
 *
 * 内容模型
 *
 * @property int           $id
 * @property string        $title
 * @property string        $keywords
 * @property string        $description
 * @property Content|Model $entity
 * @property Carbon        $created_at
 * @property Carbon        $updated_at
 * @property Carbon        $published_at
 *
 * @package App\Repositories\Content
 */
class Content extends Model implements ContentStructure
{
    use SoftDeletes, ContentMetaSetterAndGetterTrait;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function entity()
    {
        return $this->morphTo('entity', 'entity_type', 'entity_id');
    }

    /**
     * 创建内容
     *
     * @param ContentStructure|Model $entity
     *
     * @return bool
     */
    public function make(ContentStructure $entity)
    {
        if (!$entity instanceof Model) {
            throw new InvalidArgumentException();
        }

        $this->title = $entity->getTitle();
        $this->keywords = $entity->getKeywords();
        $this->description = $entity->getDescription();

        if (!$entity->exists) {
            $entity->saveOrFail();
        }

        $this->entity()->associate($entity);

        return $this->save();
    }
}