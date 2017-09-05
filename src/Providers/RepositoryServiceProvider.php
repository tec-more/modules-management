<?php namespace Tukecx\Base\ModulesManagement\Providers;

use Illuminate\Support\ServiceProvider;
use Tukecx\Base\ModulesManagement\Models\Plugins;
use Tukecx\Base\ModulesManagement\Repositories\Contracts\PluginsRepositoryContract;
use Tukecx\Base\ModulesManagement\Repositories\PluginsRepository;
use Tukecx\Base\ModulesManagement\Repositories\PluginsRepositoryCacheDecorator;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PluginsRepositoryContract::class, function () {
            $repository = new PluginsRepository(new Plugins());

            if (config('tukecx-caching.repository.enabled')) {
                return new PluginsRepositoryCacheDecorator($repository);
            }

            return $repository;
        });
    }
}
