<?php namespace Tukecx\Base\ModulesManagement\Providers;

use \Illuminate\Support\ServiceProvider;
use File;

class LoadModulesServiceProvider extends ServiceProvider
{
    protected $notLoadedModules = [];

    public function register()
    {

    }

    public function boot()
    {
        foreach (modules_management()->getAllModulesInformation()->where('type', '=', 'plugins') as $module) {
            if (array_get($module, 'enabled', null) === true) {
                /**
                 * Register module
                 */
                $moduleProvider = $module['namespace'] . '\Providers\ModuleProvider';

                if (class_exists($moduleProvider)) {
                    $this->app->register($moduleProvider);
                } else {
                    $this->notLoadedModules[] = $moduleProvider;
                }
            }
        }

        if(!app()->runningInConsole()) {
            if ($this->notLoadedModules) {
                foreach ($this->notLoadedModules as $key => $module) {
                    /**
                     * Use hook here
                     * Show the error messages
                     */
                    add_action('flash_messages', function () use ($module) {
                        echo html()->note(
                            'The base module of this class is enabled, but class not found: ' . $module . '. Please review and add the namespace of this module to composer autoload section, then run <b>composer dump-autoload</b>',
                            'error',
                            false
                        );
                    }, $key);
                }
            }
        }
    }
}
