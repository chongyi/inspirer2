<?php
/**
 * ContextResolver.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Components\CacheableContext;


interface ContextResolver
{
    public function resolve(Context $context);
}