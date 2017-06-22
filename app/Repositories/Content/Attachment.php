<?php
/**
 * Attachment.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Repositories\Content;

use App\Framework\Database\Model;
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
 * @package App\Repositories\Content
 */
class Attachment extends Model
{
    /**
     * @param string       $path
     * @param UploadedFile $file
     *
     * @return bool
     */
    public function upload($path, UploadedFile $file)
    {
        $disk = $this->getStorageDisk();

        $attachment              = new static();
        $attachment->token       = Uuid::uuid4();
        $attachment->disk        = $disk;
        $attachment->path        = $path;
        $attachment->origin_name = $file->getClientOriginalName();
        $attachment->mime        = $file->getMimeType();
        $attachment->size        = $file->getSize();

        if ($file->storeAs(dirname($path), basename($path), ['disk' => $disk]) === false) {
            return false;
        }

        return $attachment->save();
    }

    public function getStorageDisk()
    {
        return config('filesystems.default');
    }
}