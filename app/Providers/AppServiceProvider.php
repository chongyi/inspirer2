<?php

namespace App\Providers;

use App\Modules\Content\ContentServiceProvider;
use App\Repositories\Content\Attachment;
use App\Repositories\Content\ContentTreeNode;
use App\Repositories\Content\ContentEntity\Article;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation;
use Overtrue\LaravelFilesystem\Qiniu\QiniuStorageServiceProvider;

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
        $this->validateRulesRegister();


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

        $this->app->register(QiniuStorageServiceProvider::class);
        $this->app->register(ContentServiceProvider::class);
    }

    private function validateRulesRegister()
    {
        /** @var Validation\Factory $validator */
        $validator = $this->app->make(Validation\Factory::class);

        // 附件检查规则
        $validator->extend('attachment', function ($attribute, $value, $parameters, Validation\Validator $validator) {
            if (is_string($value)) {
                return Attachment::token($value)->exists();
            }

            if ($value instanceof UploadedFile) {
                if ($value->isValid()) {
                    if (count($parameters) && $parameters[0] === 'image') {
                        return in_array($value->guessExtension(), ['jpeg', 'png', 'gif', 'bmp', 'svg']);
                    }

                    return true;
                }

                return false;
            }

            return false;
        });
    }
}
