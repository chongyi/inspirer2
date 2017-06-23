<?php

use Illuminate\Database\Seeder;
use App\Repositories\User;
use App\Repositories\Content\Content;
use App\Repositories\Content\ContentEntity\Article;
use App\Repositories\Content\ContentTreeNode;

class ContentSeeder extends Seeder
{
    /**
     * @var \Faker\Generator
     */
    private $generator;

    /**
     * ContentSeeder constructor.
     */
    public function __construct()
    {
        $this->generator = app(\Faker\Generator::class);
    }


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $nodeChannel = \App\Repositories\Content\ContentNodeChannel::findByName('category');


        factory(ContentTreeNode::class, 5)->make()->each(function (ContentTreeNode $master) use ($nodeChannel) {
            $master->channel()->associate($nodeChannel)->save();

            factory(ContentTreeNode::class, rand(0, 5))->make()->each(function (ContentTreeNode $child) use ($master, $nodeChannel) {
                $child->channel()->associate($nodeChannel)->save();
                $master->addChild($child);

                factory(Article::class, rand(0, 10))->create()->each(function (Article $article) use ($child) {
                    $article->setTitle($this->generator->sentences(1, true))
                            ->setKeywords(implode(',', $this->generator->words(rand(2, 10))))
                            ->setDescription($this->generator->realText(rand(50, 200)));
                    ($content = new Content())->make($article, User::query()->find(rand(1, 20)));

                    $child->addContent($content);

                    if ($this->generator->boolean) {
                        $content->publish();
                    }
                });
            });
        });
    }
}
