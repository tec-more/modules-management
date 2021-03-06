<?php namespace Tukecx\Base\ModulesManagement\Support;

use \Closure;
use Illuminate\Support\Collection;
use Tukecx\Base\ModulesManagement\Events\ModuleDisabled;
use Tukecx\Base\ModulesManagement\Events\ModuleEnabled;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Composer;

class ModulesManagement
{
    /**
     * @var Collection|array
     */
    protected $modules;

    protected $composer;

    public function __construct(Composer $composer)
    {
        $this->composer = $composer;
        $this->composer->setWorkingPath(base_path());

        $this->modules = collect(get_all_module_information());
    }

    /**
     * @param null|string $alias
     * @return mixed|null
     */
    public function getModule($alias = null)
    {
        if (!$alias) {
            return $this->modules;
        }
        return $this->modules->where('alias', '=', $alias)->first();
    }

    /**
     * @param string $alias
     * @param array $data
     * @param Closure|null $callback
     * @return $this
     */
    public function modifyModule($alias, array $data, \Closure $callback = null)
    {
        $currentModule = $this->getModule($alias);

        if (!$currentModule) {
            throw new \RuntimeException('Module not found: ' . $alias);
        }

        if (array_get($currentModule, 'type') !== 'plugins') {
            return $this;
        }

        save_module_information($currentModule, $data);

        if ($callback) {
            call_user_func($callback);
        }

        return $this;
    }

    /**
     * @param $alias
     * @param bool $withEvent
     * return mixed
     */
    public function enableModule($alias, $withEvent = true)
    {
        $this->modifyModule($alias, ['enabled' => true], function () use ($alias, $withEvent) {
            if ($withEvent) {
                event(new ModuleEnabled($alias));
            }
        });
        $result = $this->modifyModuleAutoload($alias);

        return $result;
    }

    /**
     * @param string $alias
     * @return ModulesManagement
     */
    public function disableModule($alias, $withEvent = true)
    {
        $this->modifyModule($alias, ['enabled' => false], function () use ($alias, $withEvent) {
            if ($withEvent) {
                event(new ModuleDisabled($alias));
            }
        });

        $result = $this->modifyModuleAutoload($alias, true);

        return $result;
    }

    /**
     * Determine when module is activated
     * @param string $moduleName
     * @param \Closure|null $trueCallback
     * @param \Closure|null $falseCallback
     * @return bool
     */
    public function isActivated($moduleName, Closure $trueCallback = null, Closure $falseCallback = null)
    {
        $module = $this->getModule($moduleName);
        if ($module && isset($module['enabled']) && $module['enabled']) {
            if ($trueCallback) {
                call_user_func($trueCallback);
            }
            return true;
        }
        if ($falseCallback) {
            call_user_func($falseCallback);
        }
        return false;
    }

    /**
     * Determine when module is installed
     * @param string $moduleName
     * @param \Closure|null $trueCallback
     * @param \Closure|null $falseCallback
     * @return bool
     */
    public function isInstalled($moduleName, Closure $trueCallback = null, Closure $falseCallback = null)
    {
        $module = $this->getModule($moduleName);
        if ($module && isset($module['installed']) && $module['installed']) {
            if ($trueCallback) {
                call_user_func($trueCallback);
            }
            return true;
        }
        if ($falseCallback) {
            call_user_func($falseCallback);
        }
        return false;
    }

    /**
     * @param string $type
     * @param null|int $page
     * @param int $perPage
     * @return \Illuminate\Support\Collection
     */
    public function export($type = 'base', $page = null, $perPage = 10)
    {
        $modules = collect($this->modules)
            ->where('type', '=', $type);

        if ($page) {
            $modules = $modules->forPage($page, $perPage);
        }

        return $modules;
    }

    /**
     * Modify the composer autoload information
     * @param $alias
     * @param bool $isDisabled
     */
    public function modifyModuleAutoload($alias, $isDisabled = false)
    {
        $module = $this->getModule($alias);
        if (!$module) {
            return $this;
        }
        $moduleAutoloadType = array_get($module, 'autoload', 'psr-4');
        $relativePath = str_replace(base_path() . '/', '', str_replace('module.json', '', array_get($module, 'file', ''))) . 'src';

        $moduleNamespace = array_get($module, 'namespace');

        if (!$moduleNamespace) {
            return $this;
        }

        if (substr($moduleNamespace, -1) !== '\\') {
            $moduleNamespace .= '\\';
        }

        /**
         * Composer information
         */
        $composerContent = json_decode(File::get(base_path('composer.json')), true);
        $autoload = array_get($composerContent, 'autoload', []);

        if (!array_get($autoload, $moduleAutoloadType)) {
            $autoload[$moduleAutoloadType] = [];
        }

        if ($isDisabled === true) {
            if (isset($autoload[$moduleAutoloadType][$moduleNamespace])) {
                unset($autoload[$moduleAutoloadType][$moduleNamespace]);
            }
        } else {
            if ($moduleAutoloadType === 'classmap') {
                $autoload[$moduleAutoloadType][] = $relativePath;
            } else {
                $autoload[$moduleAutoloadType][$moduleNamespace] = $relativePath;
            }
        }
        $composerContent['autoload'] = $autoload;

        /**
         * Save file
         */
        File::put(base_path('composer.json'), json_encode_prettify($composerContent));

        return $this;
    }

    /**
     * Run command composer dump-autoload
     */
    public function refreshComposerAutoload()
    {
        $this->composer->dumpAutoloads();
        $result = response_with_messages('Composer autoload refreshed');

        return $result;
    }

    /**
     * @return array|Collection
     */
    public function getAllModulesInformation()
    {
        return $this->modules;
    }
}
