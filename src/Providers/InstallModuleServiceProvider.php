<?php namespace Tukecx\Base\ModulesManagement\Providers;

use Illuminate\Support\ServiceProvider;

class InstallModuleServiceProvider extends ServiceProvider
{
    protected $module = 'Tukecx\Base\ModulesManagement';

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        app()->booted(function () {
            $this->booted();
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    private function booted()
    {
        acl_permission()
            ->registerPermission('View plugins', 'view-plugins', $this->module);
    }
}
