<?php
/**
 * ContentPublished.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Events;

use App\Repositories\Content\Content;
use Illuminate\Queue\SerializesModels;

/**
 * Class ContentPublished
 *
 * 内容发布事件
 *
 * @package App\Events
 */
class ContentPublished
{
    use SerializesModels;

    /**
     * @var Content
     */
    public $content;

    /**
     * @var bool
     */
    public $republish;

    /**
     * ContentPublished constructor.
     *
     * @param Content $content
     * @param bool    $republish
     */
    public function __construct(Content $content, $republish)
    {
        $this->content = $content;
        $this->republish = $republish;
    }
}