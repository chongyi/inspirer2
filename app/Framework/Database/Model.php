<?php
/**
 * Model.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Framework\Database;

use App\Framework\Database\Relations\BelongsToMany;
use Closure;
use App\Framework\Database\Relations\MorphTo;
use Illuminate\Database\Eloquent\Model as LaravelModel;

abstract class Model extends LaravelModel
{
    public static $processContext = null;

    public static function contextContainer($context, Closure $callback)
    {
        static::$processContext = $context;

        $result = $callback();

        static::$processContext = null;

        return $result;
    }

    /**
     * @param null $name
     * @param null $type
     * @param null $id
     *
     * @return MorphTo
     */
    public function morphTo($name = null, $type = null, $id = null)
    {
        return parent::morphTo($name, $type, $id);
    }


    /**
     * Define a polymorphic, inverse one-to-one or many relationship.
     *
     * @param  string $name
     * @param  string $type
     * @param  string $id
     *
     * @return MorphTo
     */
    protected function morphEagerTo($name, $type, $id)
    {
        return new MorphTo(
            $this->newQuery()->setEagerLoads([]), $this, $id, null, $type, $name
        );
    }

    /**
     * Define a polymorphic, inverse one-to-one or many relationship.
     *
     * @param  string $target
     * @param  string $name
     * @param  string $type
     * @param  string $id
     *
     * @return MorphTo
     */
    protected function morphInstanceTo($target, $name, $type, $id)
    {
        $instance = $this->newRelatedInstance(
            static::getActualClassNameForMorph($target)
        );

        return new MorphTo(
            $instance->newQuery(), $this, $id, $instance->getKeyName(), $type, $name
        );
    }

    public function belongsToMany($related, $table = null, $foreignKey = null, $relatedKey = null, $relation = null)
    {
        if (is_null($relation)) {
            $relation = $this->guessBelongsToManyRelation();
        }

        $instance = $this->newRelatedInstance($related);
        $foreignKey = $foreignKey ?: $this->getForeignKey();
        $relatedKey = $relatedKey ?: $instance->getForeignKey();

        if (is_null($table)) {
            $table = $this->joiningTable($related);
        }

        return new BelongsToMany(
            $instance->newQuery(), $this, $table, $foreignKey, $relatedKey, $relation
        );
    }


}