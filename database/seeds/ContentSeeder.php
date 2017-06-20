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
        // ---------- Generate Node ----------

        $nodeChannel = new \App\Repositories\Content\ContentNodeChannel();
        $nodeChannel->node_type = \App\Repositories\Content\ContentTreeNode::class;
        $nodeChannel->name = 'category';
        $nodeChannel->display_name = 'Category';
        $nodeChannel->description = '';
        $nodeChannel->save();

        $node = new \App\Repositories\Content\ContentTreeNode();
        $node->setTitle('test')->setKeywords('')->setDescription('')->channel()->associate($nodeChannel)->save();

        // ---------- Generate content ----------

        /** @var \Faker\Generator $generator */
        $generator = app(\Faker\Generator::class);

        factory(Article::class, 50)->create()->each(function (Article $article) use ($generator, $node) {
            $article->setTitle($generator->sentences(1, true))
                    ->setKeywords(implode(',', $generator->words(rand(2, 10))))
                    ->setDescription($generator->realText(rand(50, 200)));
            ($content = new \App\Repositories\Content\Content())->make($article);

            $node->addContent($content);

            if ($generator->boolean) {
                $content->publish();
            }
        });
    }
}
