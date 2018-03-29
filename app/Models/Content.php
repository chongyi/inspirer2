<?php
/**
 * Content.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * 内容
 *
 * @property int    $id
 * @property string $name
 * @property string $title
 * @property string $description
 * @property int    $entity_id
 * @property int    $entity_type
 * @property int    $category_id
 * @property Carbon $published_at
 * @property int    $creator_id
 * @property Carbon $created_at
 *
 * @package App\Models
 */
class Content extends Model
{
    protected $table = 'contents';
}