<?php namespace Tukecx\Base\ModulesManagement\Facades;

use Illuminate\Support\Facades\Facade;

class ModulesManagementFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \Tukecx\Base\ModulesManagement\Support\ModulesManagement::class;
    }
}
