<?php
/**
 * BelongsToMany.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Framework\Database\Relations;

use Illuminate\Database\Eloquent\Relations\BelongsToMany as LaravelBelongsToMany;

/**
 * Class BelongsToMany
 *
 * @package App\Framework\Database\Relations
 */
class BelongsToMany extends LaravelBelongsToMany
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
     * @param array $columns
     *
     * @return LaravelBelongsToMany
     */
    protected function shouldSelect(array $columns = ['*'])
    {
        if ($columns == ['*']) {
            $columns = [];
            foreach ($this->columns as $column) {
                $columns[] = $this->related->getTable() . '.' . $column;
            }
        }

        return parent::shouldSelect($columns);
    }
}