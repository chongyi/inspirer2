<?php
/**
 * ContentMetaTrait.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Repositories\Traits;

/**
 * Trait ContentMetaTrait
 *
 * @package App\Repositories\Traits
 */
trait ContentMetaTrait
{
    /**
     * @var string 标题
     */
    public $title;

    /**
     * @var string 关键字
     */
    public $keywords;

    /**
     * @var string 内容描述
     */
    public $description;
}