<?php
/**
 * ContentCategorySubscriber.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Schema\Contents\Subscribers;

use App\Schema\Contents\Models\ContentCategory;

class ContentCategorySubscriber
{
    public function saving(ContentCategory $contentCategory)
    {
        if ($contentCategory->exists) {
            if ($contentCategory->parent_id) {
                $nodeMap = explode('-', $contentCategory->parent->node_map);
                $nodeMap[] = $contentCategory->id;

                sort($nodeMap, SORT_NUMERIC);
                $contentCategory->node_map = implode('-', $nodeMap);
            } else {
                $contentCategory->node_map = $contentCategory->id;
            }
        }
    }

    public function saved(ContentCategory $contentCategory)
    {
        if (!$contentCategory->node_map) {
            $contentCategory->save();
        }
    }

    public function subscribe()
    {
        ContentCategory::saved(static::class . '@saving');
        ContentCategory::saved(static::class . '@saved');
    }
}