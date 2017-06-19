<?php
/**
 * Content.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Repositories;

use App\Contracts\ContentEntity;
use App\Exceptions\InvalidArgumentException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Content
 *
 * 内容模型
 *
 * @property int $id
 * @property string $title
 * @property string $keywords
 * @property string $description
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon  $published_at
 *
 * @package App\Repositories
 */
class Content extends Model
{
    use SoftDeletes;

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
     * @param ContentEntity|Model $entity
     *
     * @return bool
     */
    public function make(ContentEntity $entity)
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