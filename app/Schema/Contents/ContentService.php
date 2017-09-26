<?php
/**
 * ContentService.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Schema\Contents;

class ContentService
{
    public function getContents($conditions, $contextName = 'content-list')
    {
        (new ContentQueryContextResolver($contextName))->resolve($conditions);
    }
}