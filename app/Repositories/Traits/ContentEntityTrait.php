<?php
/**
 * ContentEntityTrait.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Repositories\Traits;

use App\Repositories\Content\Content;

/**
 * Trait ContentEntityTrait
 *
 * @package App\Repositories\Traits
 */
trait ContentEntityTrait
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function facade()
    {
        return $this->morphOne(Content::class, 'entity', 'entity_type', 'entity_id', 'id');
    }
}