<?php
/**
 * ContentService.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Schema\Contents;

use App\Components\CacheableContext\Context;
use Illuminate\Database\Eloquent\Builder;

class ContentService
{
    public function getContents($conditions)
    {
        /** @var Builder $contentQueryBuilder */
        $contentQueryBuilder = (new Context())->setContext($conditions)
                                              ->setResolver(ContentQueryContextResolver::class)
                                              ->resolve();


    }
}