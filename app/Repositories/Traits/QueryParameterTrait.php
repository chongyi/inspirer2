<?php
/**
 * QueryParameterTrait.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Repositories\Traits;


use Illuminate\Pagination\Paginator;

trait QueryParameterTrait
{

    protected function fillPaginateParameter(array &$parameters)
    {
        if (!isset($parameters['paginate'])) {
            $parameters['paginate'] = [
                'page'     => Paginator::resolveCurrentPage('page'),
                'per_page' => $this->getPerPage(),
                'columns'  => ['*'],
            ];
        }
    }

    protected function fillParametersDefaultValue(array &$parameters, $items, $fill = null)
    {
        $items = (array)$items;

        foreach ($items as $item) {
            if (!isset($parameters[$item])) {
                $parameters[$item] = $fill;
            }
        }
    }

}