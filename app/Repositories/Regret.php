<?php
/**
 * Regret.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Repositories;

use App\Framework\Database\Model;

/**
 * Class Regret
 *
 * 待销毁的数据上下文模型
 *
 * @property int $id
 * @property string $hash
 * @property string $context
 *
 * @package App\Repositories
 */
class Regret extends Model
{
    /**
     * 记录待销毁数据
     *
     * @param       $hash
     * @param array $context
     *
     * @return static
     */
    public function record($hash, array $context = [])
    {
        $instance = new static();
        $instance->hash = $hash;
        $instance->context = json_encode($context);
        $instance->save();

        return $instance;
    }

    /**
     * 丢弃记录
     *
     * @param string $hash
     */
    public function drop($hash)
    {
        static::query()->where('hash', $hash)->delete();
    }
}