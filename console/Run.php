<?php

namespace Pbs\Logout\Console;

use PBS\Logout\Models\Settings;
use Illuminate\Console\Command;

class Run extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'logout:run';

    /**
     * @var string The console command description.
     */
    protected $description = 'Run the server that watches the users who leave the site and log them out!';

    /**
     * Execute the console command.
     * @return void
     */
    public function handle()
    {
        $port = Settings::instance()->port;
        exec('node plugins/pbs/logout/server.js ' . $port);
    }

    /**
     * Get the console command arguments.
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }

    /**
     * Get the console command options.
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }
}
