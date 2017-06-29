<?php
/**
 * ContentProcessor.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Contracts\Content;

use App\Repositories\Content\Content;
use Illuminate\Validation\ValidationException;

/**
 * Interface ContentProcessor
 *
 * @package App\Contracts\Content
 */
interface ContentProcessor
{
    /**
     * @return Content
     *
     * @throws ValidationException
     */
    public function create();

    /**
     * @param int $contentId
     *
     * @return Content
     */
    public function update($contentId);
}