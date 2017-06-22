<?php
/**
 * AttachmentTrait.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Repositories\Traits;


use App\Repositories\Content\Attachment;

trait AttachmentTrait
{
    /**
     * @param $tokenField
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function attachment($tokenField)
    {
        return $this->belongsTo(Attachment::class, $tokenField, 'token');
    }
}