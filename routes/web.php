<?php
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

$adminRoute = config('tukecx.admin_route');

$moduleRoute = 'modules-management';

/**
 * Admin routes
 */
Route::group(['prefix' => $adminRoute . '/' . $moduleRoute], function (Router $router) use ($adminRoute, $moduleRoute) {
    $router->get('', function () {
        return redirect(route('admin::core-modules.index.get'));
    });
    $router->get('plugins', 'PluginsController@getIndex')
        ->name('admin::plugins.index.get')
        ->middleware('has-permission:view-plugins');

    $router->post('plugins', 'PluginsController@postListing')
        ->name('admin::plugins.index.post')
        ->middleware('has-permission:view-plugins');

    $router->post('plugins/change-status/{module}/{status}', 'PluginsController@postChangeStatus')
        ->name('admin::plugins.change-status.post')
        ->middleware('has-role:super-admin');

    $router->post('plugins/install/{module}', 'PluginsController@postInstall')
        ->name('admin::plugins.install.post')
        ->middleware('has-role:super-admin');

    $router->post('plugins/update/{module}', 'PluginsController@postUpdate')
        ->name('admin::plugins.update.post')
        ->middleware('has-role:super-admin');

    $router->post('plugins/uninstall/{module}', 'PluginsController@postUninstall')
        ->name('admin::plugins.uninstall.post')
        ->middleware('has-role:super-admin');
});
