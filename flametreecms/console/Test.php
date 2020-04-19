<?php namespace GodSpeed\FlametreeCMS\Console;

use Cms\Classes\Theme;
use Dotenv\Dotenv;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use System\Classes\PluginManager;

class Test extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'flametreecms:test';



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
        app(EloquentFactory::class)->load(plugins_path('godspeeed/flametreecms/database/factories'));


        if(!\Schema::hasTable('system_settings')){

            $this->call('october:up', [ '--env' => 'acceptance']);
            $this->call('plugin:refresh', ['name' => 'GodSpeed.FlametreeCMS', '--env' => 'acceptance']);

        }
        Theme::setActiveTheme('flametree-theme');





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
