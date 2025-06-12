<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ReminderEventCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:event';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute reminder for upcoming events';

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
     * @return int
     */
    public function handle()
    {

        app(\App\Http\Controllers\CronjobsController::class)->reminderEvent();

        $this->info('Recordatorio de eventos ejecutado correctamente.');
        return 0;
    }
}
