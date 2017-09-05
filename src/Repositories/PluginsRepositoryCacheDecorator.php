<?php namespace Tukecx\Base\ModulesManagement\Repositories;

use Tukecx\Base\Caching\Repositories\Eloquent\EloquentBaseRepositoryCacheDecorator;

use Tukecx\Base\ModulesManagement\Repositories\Contracts\PluginsRepositoryContract;

class PluginsRepositoryCacheDecorator extends EloquentBaseRepositoryCacheDecorator  implements PluginsRepositoryContract
{
    /**
     * @param $alias
     * @return mixed
     */
    public function getByAlias($alias)
    {
        return $this->beforeGet(__FUNCTION__, func_get_args());
    }
}
