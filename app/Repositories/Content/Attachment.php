<?php
/**
 * Attachment.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Repositories\Content;

use App\Exceptions\RuntimeException;
use App\Framework\Database\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
use Ramsey\Uuid\Uuid;

/**
 * Class Attachment
 *
 * 附件资源模型
 *
 * @property string $token
 * @property string $disk
 * @property string $path
 * @property string $mime
 * @property string $origin_name
 * @property int    $size
 *
 * @method static Builder token(string $token)
 *
 * @package App\Repositories\Content
 */
class Attachment extends Model
{
    const TOKEN_FIELD_NAME = 'token';

    /**
     * @param string       $path
     * @param UploadedFile $file
     *
     * @return Attachment
     *
     * @throws RuntimeException
     */
    public function upload($path, UploadedFile $file)
    {
        $disk = $this->getStorageDisk();

        $attachment = new static();
        $attachment->token = Uuid::uuid4()->toString();
        $attachment->disk = $disk;
        $attachment->path = $path;
        $attachment->origin_name = $file->getClientOriginalName();
        $attachment->mime = $file->getMimeType();
        $attachment->size = $file->getSize();

        if ($file->storeAs(dirname($path), basename($path), ['disk' => $disk]) === false) {
            throw new RuntimeException();
        }

        $attachment->save();

        return $attachment;
    }

    /**
     * @param Builder $query
     * @param         $token
     *
     * @return Builder
     */
    public function scopeToken(Builder $query, $token)
    {
        return $query->where(static::TOKEN_FIELD_NAME, '=', $token);
    }

    public function getStorageDisk()
    {
        return config('filesystems.default');
    }
}