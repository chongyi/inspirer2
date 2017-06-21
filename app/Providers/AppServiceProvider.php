<?php

namespace App\Providers;

use App\Repositories\Content\ContentTreeNode;
use App\Repositories\Content\ContentType\Article;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'tree_node' => ContentTreeNode::class,
            'article'   => Article::class,
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // 调试模式或本地模式下开启
        if (env('APP_DEBUG') || $this->app->environment() == 'local') {
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }

        //
    }
}
