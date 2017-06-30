<?php
/**
 * QueryCache.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Framework\Database;


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
    protected $switch = true;

    protected $parameters = [];

    protected $queryCallback;

    protected $expiredAt;

    protected $forever = false;

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

    public function cache($switch)
    {
        $this->switch = $switch;
        return $this;
    }

    public function parameters(array $parameters)
    {
        $this->parameters = $parameters;
        return $this;
    }

    public function expiredAt(Carbon $expiredAt)
    {
        $this->expiredAt = $expiredAt;
        return $this;
    }

    public function forever($switch)
    {
        $this->forever = $switch;
        return $this;
    }

    public function query(\Closure $callback)
    {
        $this->queryCallback = $callback;
        return $this;
    }

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

    protected function getParametersHash(array $parameters)
    {
        return sha1(serialize(ksort($parameters)));
    }
}