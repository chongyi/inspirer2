<?php
/**
 * ContentCategorySubscriber.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Schema\Contents\Subscribers;

use App\Schema\Contents\Models\ContentCategory;
use Illuminate\Database\Eloquent\Model;

class ContentCategorySubscriber
{
    public function saving(ContentCategory $contentCategory)
    {
        if ($contentCategory->exists) {
            if (!$contentCategory->node_map) {
                if ($contentCategory->parent_id) {
                    $contentCategory->node_map = $contentCategory->parent->node_map . ',' . $contentCategory->id;
                } else {
                    $contentCategory->node_map = $contentCategory->id;
                }
            } else {
                $dirty = $contentCategory->getDirty();

                if (isset($dirty['parent_id'])) {
                    /** @var ContentCategory $parent */
                    $parent = ContentCategory::query()->find($dirty['parent_id']);
                    $parentNodeMap = $parent->node_map;

                    $selfNodeMap = $contentCategory->node_map;
                    $newNodeMap = $parentNodeMap . ',' . $contentCategory->id;

                    $childrenIds = $contentCategory->depthChildren()->pluck('id')->all();
                    $contentCategory->node_map = $newNodeMap;

                    $connection = Model::resolveConnection();
                    ContentCategory::query()->whereIn('id', $childrenIds)->update([
                        'node_map' => $connection->raw("replace(`node_map`, '{$selfNodeMap},', '{$newNodeMap},')"),
                    ]);
                }
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
        ContentCategory::saving(static::class . '@saving');
        ContentCategory::saved(static::class . '@saved');
    }
}