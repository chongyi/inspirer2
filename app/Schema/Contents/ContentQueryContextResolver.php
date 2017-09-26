<?php
/**
 * ContentQueryContextResolver.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Schema\Contents;

use App\Components\CacheableContext\Context;
use App\Components\CacheableContext\ContextResolver;
use App\Schema\Contents\Models\Content;
use Illuminate\Database\Eloquent\Builder;

class ContentQueryContextResolver implements ContextResolver
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

    public function resolve(Context $context)
    {
        $contentQueryBuilder = Content::query();

        if (isset($context['title']) && ($title = trim($context['title'])) !== '') {
            $contentQueryBuilder->where('title', 'like', "%{$title}%");
        }

        if (isset($context['summary']) && ($summary = trim($context['summary']))) {
            $contentQueryBuilder->where(function (Builder $query) use ($summary) {
                $query->where('description', 'like', "%{$summary}%")->orWhere('keywords', 'like', "%{$summary}%");
            });
        }

        if (isset($context['id']) && is_numeric($id = $context['id'])) {
            if ($id > 0) {
                $contentQueryBuilder->where('id', $id);
            }
        }

        $sortField = $context['sort_field'] ?? 'create_time';
        $sortMethod = $context['sort_method'] ?? 'desc';

        if (in_array($sortField, array_keys($this->supportSortField))) {
            if (in_array($sortMethod, $this->supportSortMethod)) {
                $contentQueryBuilder->orderBy($this->supportSortField[$sortField], $sortMethod);
            }
        }

        return $contentQueryBuilder;
    }
}