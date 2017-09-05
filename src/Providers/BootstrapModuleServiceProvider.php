<?php namespace Tukecx\Base\ModulesManagement\Providers;

use Illuminate\Support\ServiceProvider;

class BootstrapModuleServiceProvider extends ServiceProvider
{
    protected $module = 'Tukecx\Base\ModulesManagement';

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Determine when our app booted
         */
        app()->booted(function () {
            \DashboardMenu::registerItem([
                'id' => 'tukecx-plugins',
                'priority' => 1001,
                'parent_id' => null,
                'heading' => '扩展&主题',
                'title' => '插件',
                'font_icon' => 'icon-paper-plane',
                'link' => route('admin::plugins.index.get'),
                'css_class' => null,
                'permissions' => ['view-plugins'],
            ]);
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
}
