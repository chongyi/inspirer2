<?php
/**
 * Content.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Repositories\Content;

use App\Contracts\ContentStructure;
use App\Events\ContentPublished;
use App\Exceptions\InvalidArgumentException;
use App\Exceptions\OperationRejectedException;
use App\Repositories\Traits\ContentMetaSetterAndGetterTrait;
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

    /**
     * 发布内容
     *
     * @param bool $republish 是否重新发布
     *
     * @return bool
     */
    public function publish($republish = false)
    {
        if (!$this->exists) {
            throw new OperationRejectedException();
        }

        if ($this->published_at && !$republish) {
            return false;
        }

        $this->published_at = Carbon::now();
        $this->save();

        if (isset(static::$dispatcher)) {
            static::$dispatcher->dispatch(new ContentPublished($this, $republish));
        }

        return true;
    }
}