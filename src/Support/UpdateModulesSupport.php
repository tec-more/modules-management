<?php namespace Tukecx\Base\ModulesManagement\Support;

class UpdateModulesSupport
{
    /**
     * @var array
     */
    protected $batches = [];

    /**
     * @param $moduleAlias
     * @param array $batches
     * @return $this
     */
    public function registerUpdateBatches($moduleAlias, array $batches)
    {
        $this->batches[$moduleAlias] = $batches;

        return $this;
    }

    /**
     * @param $moduleAlias
     * @return $this
     */
    public function loadBatches($moduleAlias)
    {
        $currentModuleInformation = get_module_information($moduleAlias);
        if (!$currentModuleInformation) {
            return $this;
        }

        $installedModuleVersion = array_get($currentModuleInformation, 'installed_version');
        foreach ($this->batches[$moduleAlias] as $version => $batch) {
            if (!$installedModuleVersion || version_compare($version, $installedModuleVersion, '>')) {
                require $batch;
            }
        }
        return $this;
    }
}
