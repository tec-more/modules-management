<?php namespace Tukecx\Base\ModulesManagement\Hook;


class RegisterDashboardStats
{
    protected $modules;

    protected $plugins;

    public function __construct()
    {
        $this->modules = collect(get_all_module_information());
        $this->plugins = $this->modules->where('type', '=', 'plugins');
    }

    public function handle()
    {
        echo view('tukecx-modules-management::admin.dashboard-stats.stat-box', [
            'count' => $this->plugins->count()
        ]);
    }
}
