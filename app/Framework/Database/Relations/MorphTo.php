<?php
/**
 * MorphTo.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Framework\Database\Relations;

use Illuminate\Database\Eloquent\Relations\MorphTo as LaravelMorphTo;

class MorphTo extends LaravelMorphTo
{
    protected $columns = ['*'];

    /**
     * @param array $columns
     *
     * @return $this
     */
    public function setColumns($columns = ['*'])
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * Get all of the relation results for a type.
     *
     * @param  string  $type
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getResultsByType($type)
    {
        $instance = $this->createModelByType($type);

        $query = $this->replayMacros($instance->newQuery())
            ->mergeConstraintsFrom($this->getQuery())
            ->with($this->getQuery()->getEagerLoads());

        $query->select($this->columns);

        return $query->whereIn(
            $instance->getTable().'.'.$instance->getKeyName(), $this->gatherKeysByType($type)
        )->get();
    }
}