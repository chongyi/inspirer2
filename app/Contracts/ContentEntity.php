<?php
/**
 * ContentEntity.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Contracts;

/**
 * Interface ContentEntity
 *
 * 内容实体
 *
 * @package App\Contracts
 */
interface ContentEntity
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