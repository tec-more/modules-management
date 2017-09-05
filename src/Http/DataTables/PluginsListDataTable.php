<?php namespace Tukecx\Base\ModulesManagement\Http\DataTables;

use Tukecx\Base\Core\Http\DataTables\AbstractDataTables;

class PluginsListDataTable extends AbstractDataTables
{
    protected $repository;

    public function __construct()
    {
        $this->repository = modules_management()->export('plugins')->values();

        parent::__construct();
    }

    /**
     * @return string
     */
    public function run()
    {
        $this->setAjaxUrl(route('admin::plugins.index.post'), 'POST');

        $this
            ->addHeading('name', '名称', '20%')
            ->addHeading('description', '描述', '50%')
            ->addHeading('actions', '动作', '30%');

        $this->setColumns([
            ['data' => 'name', 'name' => 'name', 'searchable' => false, 'orderable' => false],
            ['data' => 'description', 'name' => 'description', 'searchable' => false, 'orderable' => false],
            ['data' => 'actions', 'name' => 'actions', 'searchable' => false, 'orderable' => false],
        ]);

        return $this->view();
    }

    /**
     * @return $this
     */
    protected function fetch()
    {
        $this->fetch = datatable()->of($this->repository)
            ->editColumn('description', function ($item) {
                return array_get($item, 'description') . '<br><br>'
                    . '作者: ' . array_get($item, 'author') . '<br><br>'
                    . '版本: <b>' . array_get($item, 'version', '...') . '</b>' . '<br>'
                    . '安装版本: <b>' . array_get($item, 'installed_version', '...') . '</b>';
            })
            ->addColumn('actions', function ($item) {
                $activeBtn = (!array_get($item, 'enabled')) ? form()->button('Active', [
                    'title' => '激活插件',
                    'data-ajax' => route('admin::plugins.change-status.post', [
                        'module' => array_get($item, 'alias'),
                        'status' => 1,
                    ]),
                    'data-method' => 'POST',
                    'data-toggle' => 'confirmation',
                    'class' => 'btn btn-outline green btn-sm ajax-link',
                ]) : '';

                $disableBtn = (array_get($item, 'enabled')) ? form()->button('禁用', [
                    'title' => '禁用插件',
                    'data-ajax' => route('admin::plugins.change-status.post', [
                        'module' => array_get($item, 'alias'),
                        'status' => 0,
                    ]),
                    'data-method' => 'POST',
                    'data-toggle' => 'confirmation',
                    'class' => 'btn btn-outline yellow-lemon btn-sm ajax-link',
                ]) : '';

                $installBtn = (array_get($item, 'enabled') && !array_get($item, 'installed')) ? form()->button('安装', [
                    'title' => '安装此插件的依赖关系',
                    'data-ajax' => route('admin::plugins.install.post', [
                        'module' => array_get($item, 'alias'),
                    ]),
                    'data-method' => 'POST',
                    'data-toggle' => 'confirmation',
                    'class' => 'btn btn-outline blue btn-sm ajax-link',
                ]) : '';

                $updateBtn = (
                    array_get($item, 'enabled') &&
                    array_get($item, 'installed') &&
                    version_compare(array_get($item, 'installed_version'), array_get($item, 'version'), '<')
                )
                    ? form()->button('更新', [
                        'title' => '更新插件',
                        'data-ajax' => route('admin::plugins.update.post', [
                            'module' => array_get($item, 'alias'),
                        ]),
                        'data-method' => 'POST',
                        'data-toggle' => 'confirmation',
                        'class' => 'btn btn-outline purple btn-sm ajax-link',
                    ])
                    : '';

                $uninstallBtn = (array_get($item, 'enabled') && array_get($item, 'installed')) ? form()->button('卸载', [
                    'title' => '卸载此插件的依赖关系',
                    'data-ajax' => route('admin::plugins.uninstall.post', [
                        'module' => array_get($item, 'alias'),
                    ]),
                    'data-method' => 'POST',
                    'data-toggle' => 'confirmation',
                    'class' => 'btn btn-outline red-sunglo btn-sm ajax-link',
                ]) : '';

                return $activeBtn . $disableBtn . $installBtn . $updateBtn . $uninstallBtn;
            });

        return $this;
    }
}
