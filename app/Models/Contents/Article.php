<?php
/**
 * Article.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Models\Contents;

/**
 * 文章
 *
 * @property int    $id
 * @property string $origin_source
 * @property int    $formatter
 *
 * @package App\Models\Contents
 */
class Article extends Entity
{
    protected $table = 'content_entity_articles';
}