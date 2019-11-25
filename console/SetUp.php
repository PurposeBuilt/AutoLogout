<?php namespace PBS\Logout\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SetUp extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'logout:setup';

    /**
     * @var string The console command description.
     */
    protected $description = 'Setting up and downloading the dependencies of the plugin.';

    /**
     * Execute the console command.
     * @return void
     */
    public function handle()
    {
        $bar = $this->output->createProgressBar(3);

        $bar->advance();

        $this->output->writeln("\n Installing NPM dependencies.");

        $bar->advance();

        exec('cd plugins/pbs/logout && npm install');

        $bar->advance();

        $this->info("\n Done!");
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
