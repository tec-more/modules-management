<?php namespace Tukecx\Base\ModulesManagement\Providers;

use Illuminate\Support\ServiceProvider;

class ConsoleServiceProvider extends ServiceProvider
{
    protected $module = 'Tukecx\Base\ModulesManagement';

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->generatorCommands();
        $this->otherCommands();
    }

    private function generatorCommands()
    {
        $this->commands([
            \Tukecx\Base\ModulesManagement\Console\Generators\MakeModule::class,
            \Tukecx\Base\ModulesManagement\Console\Generators\MakeProvider::class,
            \Tukecx\Base\ModulesManagement\Console\Generators\MakeController::class,
            \Tukecx\Base\ModulesManagement\Console\Generators\MakeMiddleware::class,
            \Tukecx\Base\ModulesManagement\Console\Generators\MakeRequest::class,
            \Tukecx\Base\ModulesManagement\Console\Generators\MakeModel::class,
            \Tukecx\Base\ModulesManagement\Console\Generators\MakeRepository::class,
            \Tukecx\Base\ModulesManagement\Console\Generators\MakeFacade::class,
            \Tukecx\Base\ModulesManagement\Console\Generators\MakeService::class,
            \Tukecx\Base\ModulesManagement\Console\Generators\MakeSupport::class,
            \Tukecx\Base\ModulesManagement\Console\Generators\MakeView::class,
            \Tukecx\Base\ModulesManagement\Console\Generators\MakeMigration::class,
            \Tukecx\Base\ModulesManagement\Console\Generators\MakeCommand::class,
            \Tukecx\Base\ModulesManagement\Console\Generators\MakeDataTable::class,
            \Tukecx\Base\ModulesManagement\Console\Generators\MakeCriteria::class,
        ]);
    }

    private function otherCommands()
    {
        $this->commands([
            \Tukecx\Base\ModulesManagement\Console\Commands\InstallModuleCommand::class,
            \Tukecx\Base\ModulesManagement\Console\Commands\UpdateModuleCommand::class,
            \Tukecx\Base\ModulesManagement\Console\Commands\UninstallModuleCommand::class,
            \Tukecx\Base\ModulesManagement\Console\Commands\DisableModuleCommand::class,
            \Tukecx\Base\ModulesManagement\Console\Commands\EnableModuleCommand::class,
            \Tukecx\Base\ModulesManagement\Console\Commands\ExportModuleCommand::class,
            \Tukecx\Base\ModulesManagement\Console\Commands\GetAllModulesCommand::class,
        ]);
    }
}
