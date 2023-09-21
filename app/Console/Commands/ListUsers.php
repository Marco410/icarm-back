<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ListUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'list:Users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List data users';

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
        $this->table(['ID', 'Name', 'Email', 'Created at'], User::all(['id','username', 'email', 'created_at'])->take(100)->toArray());
        $this->info('Success!');
    }
}
