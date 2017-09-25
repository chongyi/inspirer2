<?php
/**
 * Article.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Schema\Contents\Models\ContentEntities;

use App\Schema\Contents\Models\ContentEntity;

/**
 * Class Article
 *
 * @property string $body
 * @property string $origin_source
 *
 * @package App\Schema\Contents\Models\ContentEntities
 */
class Article extends ContentEntity
{
    protected $table = 'content_entity_articles';


}