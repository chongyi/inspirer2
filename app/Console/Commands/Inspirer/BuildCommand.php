<?php

namespace App\Console\Commands\Inspirer;

use Illuminate\Console\Command;

class BuildCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inspirer:build';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inspirer project build (or rebuild) command.';

    /**
     * Create a new command instance.
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
        // call migrate command
        $this->call('migrate:refresh');
        $this->call('db:seed');

        return 0;
    }
}
