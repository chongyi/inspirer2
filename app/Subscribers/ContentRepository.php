<?php
/**
 * ContentRepository.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Subscribers;

use App\Repositories\Content\ContentTreeNode;
use Illuminate\Contracts\Events\Dispatcher;

class ContentRepository
{
    public function onContentTreeNodeSaved(ContentTreeNode $node)
    {
        if ($node->path) {
            return true;
        }

        $node->path = $node->id;
        return $node->save();
    }

    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            'eloquent.saved: ' . ContentTreeNode::class,
            static::class . '@onContentTreeNodeSaved'
        );
    }
}