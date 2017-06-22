<?php
/**
 * DatabaseEventSubscriber.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Subscribers;


use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Database\Events\QueryExecuted;

class DatabaseEventSubscriber
{
    /**
     * @var Application
     */
    protected $application;

    /**
     * DatabaseEventSubscriber constructor.
     *
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }


    public function query(QueryExecuted $queryExecuted)
    {
        if ($this->application->environment() === 'local') {
            $this->application->make(Log::class)->debug('Query executed: ' . $queryExecuted->sql, [
                'bindings'        => $queryExecuted->bindings,
                'time'            => $queryExecuted->time,
                'connection' => $queryExecuted->connectionName,
            ]);
        }
    }

    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            QueryExecuted::class,
            static::class . '@query'
        );
    }
}