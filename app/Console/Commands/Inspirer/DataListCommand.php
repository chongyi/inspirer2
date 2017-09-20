<?php

namespace App\Console\Commands\Inspirer;

use App\Framework\Database\Model;
use Illuminate\Console\Command;
use App\Repositories;

class DataListCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inspirer:data-list {--P|page=1} {--S|size=15} {source}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List data, if you want to know which source is support, please type "support".';

    protected $support = [
        'user'    => [Repositories\User::class, ['id', 'nickname', 'email', 'created_at', 'updated_at']],
        'content' => [Repositories\Content\Content::class, ['id', 'title', 'author_name', 'created_at', 'updated_at']],
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $source = $this->argument('source');

        if ($source === 'support') {
            $rows = [];
            foreach ($this->support as $key => list($model, $fields)) {
                $rows[] = [
                    $key,
                    $model,
                    implode(',', $fields)
                ];
            }

            $this->table(['Source Code', 'Model Name', 'Fields'], $rows);
        } elseif (isset($this->support[$source])) {
            /** @var Model $model */
            list($model, $fields) = $this->support[$source];

            $data = $model::query()->forPage($this->option('page'), $this->option('size'))->get($fields);
            $this->table($fields, $data);
        }
    }
}
