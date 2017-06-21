<?php
/**
 * ContentClassifyTrait.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Repositories\Traits;

use App\Repositories\Content\ContentTreeNode;

/**
 * Trait ContentClassifyTrait
 *
 * @property ContentTreeNode[] $categories
 *
 * @package App\Repositories\Traits
 */
trait ContentClassifyTrait
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->nodes();
    }
}