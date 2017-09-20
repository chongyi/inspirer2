<?php
/**
 * QueryCache.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Framework\Database;


use App\Exceptions\OperationRejectedException;
use Carbon\Carbon;
use Illuminate\Container\Container;
use Illuminate\Contracts\Cache\Repository;

/**
 * Class QueryCache
 *
 * 查询缓存
 *
 * @package App\Framework\Database
 */
class QueryCache
{
    /**
     * @var bool
     */
    protected $switch = true;

    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @var \Closure
     */
    protected $queryCallback;

    /**
     * @var Carbon
     */
    protected $expiredAt;

    /**
     * @var bool
     */
    protected $forever = false;

    /**
     * @var string
     */
    protected $prefix;

    /**
     * @var Repository
     */
    protected $repository;

    /**
     * QueryCache constructor.
     */
    public function __construct()
    {
        $this->expiredAt(Carbon::now()->addMinutes(10));
        $this->repository = Container::getInstance()->make('cache');
    }

    /**
     * @param $switch
     *
     * @return $this
     */
    public function cache($switch)
    {
        $this->switch = $switch;
        return $this;
    }

    /**
     * 查询参数
     *
     * @param array $parameters
     *
     * @return $this
     */
    public function parameters(array $parameters)
    {
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * 过期时间
     *
     * @param Carbon $expiredAt
     *
     * @return $this
     */
    public function expiredAt(Carbon $expiredAt)
    {
        $this->expiredAt = $expiredAt;
        return $this;
    }

    /**
     * 是否永不过期
     *
     * @param bool $switch
     *
     * @return $this
     */
    public function forever($switch)
    {
        $this->forever = $switch;
        return $this;
    }

    /**
     * 查询过程
     *
     * @param \Closure $callback 该回调必须返回一个值用于缓存或作为结果
     *
     * @return $this
     */
    public function query(\Closure $callback)
    {
        $this->queryCallback = $callback;
        return $this;
    }

    /**
     * 缓存键前缀
     *
     * 若为调用该方法设置前缀，最终获取数据时会导致异常。
     *
     * @param string $prefix 不为空的文本，需要避免与其他前缀冲突引发的缓存取值异常
     *
     * @return $this
     */
    public function prefix($prefix)
    {
        $this->prefix = $prefix;
        return $this;
    }

    /**
     * 获取结果集
     *
     * @return mixed
     */
    public function get()
    {
        $hash = $this->getParametersHash($this->parameters);
        if ($this->switch) {
            if ($this->repository->has($hash)) {
                return $this->repository->get($hash);
            }
        }

        $cached = call_user_func($this->queryCallback, $this->parameters);

        if ($this->switch) {
            if ($this->forever) {
                $this->repository->forever($hash, $cached);
            } else {
                $this->repository->put($hash, $cached, $this->expiredAt);
            }
        }

        return $cached;
    }

    /**
     * @param array $parameters
     *
     * @return string
     */
    protected function getParametersHash(array $parameters)
    {
        if (!$this->prefix) {
            throw new OperationRejectedException();
        }

        return $this->prefix . ':' . sha1(serialize(ksort($parameters)));
    }
}