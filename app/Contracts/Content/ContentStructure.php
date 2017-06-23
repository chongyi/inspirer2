<?php
/**
 * ContentStructure.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Contracts\Content;

/**
 * Interface ContentStructure
 *
 * 内容结构接口
 *
 * @package App\Contracts\Content
 */
interface ContentStructure
{
    /**
     * @return string
     */
    public function getTitle();

    /**
     * @return string
     */
    public function getKeywords();

    /**
     * @return string
     */
    public function getDescription();
}