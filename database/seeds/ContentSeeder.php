<?php

use Illuminate\Database\Seeder;
use App\Repositories\Content\ContentType\Article;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** @var \Faker\Generator $generator */
        $generator = app(\Faker\Generator::class);

        factory(Article::class, 50)->create()->each(function (Article $article) use ($generator) {
            $article->setTitle($generator->sentences(1, true))
                    ->setKeywords(implode(',', $generator->words(rand(2, 10))))
                    ->setDescription($generator->realText(rand(50, 200)));
            ($content = new \App\Repositories\Content\Content())->make($article);

            if ($generator->boolean) {
                $content->publish();
            }
        });
    }
}
