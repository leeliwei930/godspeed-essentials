<?php namespace GodSpeed\FlametreeCMS\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use System\Classes\UpdateManager;

class Uninstall extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'flametreecms:uninstall';

    /**
     * @var string The console command description.
     */
    protected $description = 'No description provided yet...';

    /**
     * Execute the console command.
     * @return void
     */
    public function handle()
    {


            $manager = UpdateManager::instance()->setNotesOutput($this->output);
        $manager->rollbackPlugin('SureSoftware.PowerSEO');

        $manager->rollbackPlugin('GodSpeed.FlametreeCMS');
            $manager->rollbackPlugin('RainLab.Blog');
            $manager->rollbackPlugin('RainLab.User');
            $manager->rollbackPlugin('RainLab.Pages');

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
