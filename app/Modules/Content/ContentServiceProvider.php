<?php
/**
 * ContentServiceProvider.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Modules\Content;


use App\Contracts\Content\ContentProcessor;
use App\Exceptions\InvalidArgumentException;
use App\Modules\Content\ContentProcessors\ArticleProcessor;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class ContentServiceProvider extends ServiceProvider
{
    protected $typeMap = [
        'article' => ArticleProcessor::class,
    ];

    public function register()
    {
        $this->app->bind(ContentProcessor::class, function (Application $application, $type) {
            if (is_array($type)) {
                $type = $type[0];
            }

            if (!isset($this->typeMap[$type])) {
                throw new InvalidArgumentException();
            }

            return $application->make($this->typeMap[$type]);
        });
    }
}