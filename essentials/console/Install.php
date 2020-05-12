<?php namespace GodSpeed\Essentials\Console;

use Cms\Classes\Theme;
use Dotenv\Dotenv;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use System\Classes\PluginManager;
use System\Classes\UpdateManager;

class Install extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'essentials:install';



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
        app(EloquentFactory::class)->load(plugins_path('godspeeed/essentials/database/factories'));


        if (!\Schema::hasTable('backend_users')) {
            $this->call('october:up');
        } else {

            $manager = UpdateManager::instance()->setNotesOutput($this->output);


            $this->output->writeln('<info>Reinstalling plugin...</info>');
            $manager->updatePlugin('RainLab.Pages');

            $manager->updatePlugin('RainLab.User');
            $manager->updatePlugin('RainLab.Blog');
            $manager->updatePlugin('Arcane.Seo');

            $manager->updatePlugin('GodSpeed.Essentials');

        }

        if(Theme::getActiveThemeCode() !== 'flametree-theme'){
            $this->call('theme:use', ['name' => 'flametree-theme']);

        }
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
