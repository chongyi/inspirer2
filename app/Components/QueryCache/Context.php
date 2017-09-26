<?php
/**
 * Context.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Components\QueryCache;


use Closure;
use Illuminate\Contracts\Cache\Factory;

abstract class Context
{
    /**
     * @var Factory
     */
    protected $cache;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var Closure
     */
    protected $checker;

    /**
     * @var bool
     */
    protected $useCache = true;

    /**
     * @var string
     */
    protected static $cachePrefix = 'query-cache:';

    /**
     * Context constructor.
     *
     * @param string  $name
     * @param Factory $cache
     */
    public function __construct($name, Factory $cache = null)
    {
        if (is_null($cache)) {
            $cache = app()->make('cache');
        }

        $this->name = self::getCachePrefix() . $name;
        $this->cache = $cache;
    }

    /**
     * @return string
     */
    public static function getCachePrefix()
    {
        return self::$cachePrefix;
    }

    /**
     * @param string $cachePrefix
     */
    public static function setCachePrefix(string $cachePrefix)
    {
        self::$cachePrefix = $cachePrefix;
    }

    /**
     * @param $conditions
     *
     * @return mixed
     */
    abstract protected function resolving($conditions);

    /**
     * @param Closure $checker
     *
     * @return Context
     */
    public function setChecker(Closure $checker = null)
    {
        $this->checker = $checker;
        return $this;
    }

    /**
     * @param bool $switch
     *
     * @return $this
     */
    public function useCache(bool $switch = true)
    {
        $this->useCache = $switch;
        return $this;
    }

    /**
     * @return $this
     */
    public function withoutCache()
    {
        $this->useCache = false;
        return $this;
    }

    /**
     * @param       $conditions
     * @param array $options
     *
     * @return mixed
     */
    public function resolve($conditions, $options = [])
    {
        if ($this->useCache && $this->cache->has($this->name)) {
            $materials = $this->cache->get($this->name);
            $hash = $this->getConditionsHash($conditions);
            $key = $this->getCacheKey($hash);

            if (isset($materials[$hash]) && $this->cache->store()->has($key)) {
                if (is_null($this->checker) || ($this->checker)($materials[$hash], $conditions)) {
                    return $this->cache->store()->get($key);
                }
            }
        }

        return $this->storeResolvedDataIntoCache($conditions, $options);
    }

    /**
     * @param $conditions
     *
     * @return string
     */
    protected function getConditionsHash($conditions)
    {
        return sha1(serialize($conditions));
    }

    /**
     * @param $conditionsHash
     *
     * @return string
     */
    protected function getCacheKey($conditionsHash)
    {
        return $this->name . '.' . $conditionsHash;
    }

    /**
     * @param       $conditions
     * @param array $options
     *
     * @return mixed
     */
    protected function storeResolvedDataIntoCache($conditions, $options = [])
    {
        $resolved = $this->resolving($conditions);
        if ($this->useCache) {
            $hash = $this->getConditionsHash($conditions);

            $materials = $this->cache->store()->get($this->name, []);
            $materials[$hash] = $options;

            $this->cache->store()->forever($this->name, $materials);
            $this->cache->store()->put($this->getCacheKey($hash), $resolved, $options['lifetime'] ?? 10);
        }

        return $resolved;
    }
}