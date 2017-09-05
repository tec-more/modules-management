<?php namespace Tukecx\Base\ModulesManagement\Repositories;

use Tukecx\Base\Caching\Services\Contracts\CacheableContract;
use Tukecx\Base\Caching\Services\Traits\Cacheable;
use Tukecx\Base\Core\Repositories\Eloquent\EloquentBaseRepository;
use Tukecx\Base\ModulesManagement\Repositories\Contracts\PluginsRepositoryContract;

class PluginsRepository extends EloquentBaseRepository implements PluginsRepositoryContract, CacheableContract
{
    use Cacheable;

    protected $rules = [
        'alias' => 'string|max:255|alpha_dash',
        'installed_version' => 'string|max:255',
    ];

    protected $editableFields = [
        'alias',
        'installed_version',
        'enabled',
        'installed',
    ];

    /**
     * @param $alias
     * @return mixed
     */
    public function getByAlias($alias)
    {
        return $this->model->where('alias', '=', $alias)->first();
    }
}
