<?php

use Illuminate\Database\Seeder;
use App\Repositories\User;
use App\Repositories\Content\Content;
use App\Repositories\Content\ContentEntity\Article;
use App\Repositories\Content\ContentTreeNode as Node;

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


        factory(Node::class, 5)->make()->each(function (Node $master) use ($nodeChannel) {
            $master->channel()->associate($nodeChannel)->save();

            factory(Node::class, rand(0, 2))->make()->each(function (Node $child) use ($master) {
                $master->addChild($child);

                $this->deep($child);
            });
        });
    }

    private function deep(Node $parent, $deep = 0)
    {
        if ($deep == 2) {
            factory(Article::class, rand(0, 10))->create()->each(function (Article $article) use ($parent) {
                $article->setTitle($this->generator->sentences(1, true))
                        ->setKeywords(implode(',', $this->generator->words(rand(2, 10))))
                        ->setDescription($this->generator->realText(rand(50, 200)));
                ($content = new Content())->make($article, User::query()->find(rand(1, 20)));

                $parent->addContent($content);

                if ($this->generator->boolean) {
                    $content->publish();
                }
            });

            return;
        }

        factory(Node::class, rand(0, 5))->make()->each(function (Node $child) use ($parent, $deep) {
            $parent->addChild($child);

            $this->deep($child, $deep + 1);
        });
    }
}
