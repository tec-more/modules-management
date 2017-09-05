<?php namespace Tukecx\Base\ModulesManagement\Facades;

use Illuminate\Support\Facades\Facade;
use Tukecx\Base\ModulesManagement\Support\UpdateModulesSupport;

class UpdateModulesFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return UpdateModulesSupport::class;
    }
}
