<?php
/**
 * ContentQueryContextResolver.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Schema\Contents;

use App\Components\QueryCache\Context;
use App\Schema\Contents\Models\Content;
use Illuminate\Database\Eloquent\Builder;

class ContentQueryContextResolver extends Context
{
    protected $supportSortField = [
        'title' => 'title',
        'create_time' => 'created_at',
        'update_time' => 'updated_at',
        'id' => 'id',
    ];

    protected $supportSortMethod = [
        'desc', 'asc',
    ];

    protected function resolving($conditions)
    {
        $contentQueryBuilder = Content::query();

        if (isset($conditions['query']['title']) && ($title = trim($conditions['query']['title'])) !== '') {
            $contentQueryBuilder->where('title', 'like', "%{$title}%");
        }

        if (isset($conditions['query']['summary']) && ($summary = trim($conditions['query']['summary']))) {
            $contentQueryBuilder->where(function (Builder $query) use ($summary) {
                $query->where('description', 'like', "%{$summary}%")->orWhere('keywords', 'like', "%{$summary}%");
            });
        }

        if (isset($conditions['query']['id']) && is_numeric($id = $conditions['query']['id'])) {
            if ($id > 0) {
                $contentQueryBuilder->where('id', $id);
            }
        }

        $sortField = $conditions['query']['sort_field'] ?? 'create_time';
        $sortMethod = $conditions['query']['sort_method'] ?? 'desc';

        if (in_array($sortField, array_keys($this->supportSortField))) {
            if (in_array($sortMethod, $this->supportSortMethod)) {
                $contentQueryBuilder->orderBy($this->supportSortField[$sortField], $sortMethod);
            }
        }

        return $contentQueryBuilder->get();
    }
}