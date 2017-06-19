<?php
/**
 * ContentStructure.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Contracts;

/**
 * Interface ContentStructure
 *
 * 内容结构接口
 *
 * @package App\Contracts
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