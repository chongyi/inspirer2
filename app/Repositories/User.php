<?php
/**
 * User.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Repositories;

use App\Repositories\Content\Content;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User
 *
 * 用户模型
 *
 * @property int    $id
 * @property string $nickname
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Repositories
 */
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contents()
    {
        return $this->hasMany(Content::class, 'author_id', 'id');
    }

    /**
     * 获取昵称
     *
     * @return string
     */
    public function getNickname()
    {
        return $this->nickname;
    }
}
