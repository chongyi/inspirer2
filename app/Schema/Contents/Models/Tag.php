<?php
/**
 * Tag.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Schema\Contents\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Tag
 *
 * @property int $id
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Schema\Contents\Models
 */
class Tag extends Model
{
    protected $table = 'content_tags';
}