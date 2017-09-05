<?php namespace Tukecx\Base\ModulesManagement\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Composer;
use Illuminate\Filesystem\Filesystem;

class ExportModuleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:base:export {alias}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export base modules from vendor to base/';

    /**
     * @var array
     */
    protected $container = [];


    /**
     * @var Composer
     */
    protected $composer;

    /**
     * @var Filesystem
     */
    protected $files;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Composer $composer, Filesystem $filesystem)
    {
        parent::__construct();

        $this->composer = $composer;
        $this->composer->setWorkingPath(base_path());

        $this->files = $filesystem;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->container['alias'] = $this->argument('alias');

        $module = get_modules_by_type('base')
            ->where('alias', '=', $this->container['alias'])->first();

        if(!$module) {
            $this->error("Module not exists");
        }

        $moduleVendorPath = get_base_folder(array_get($module, 'file'));

        $relativePath = str_replace(base_path('vendor/tec-more/'), '', $moduleVendorPath);

        try {
            $this->files->makeDirectory(tukecx_base_path($relativePath), 755, false, true);
            $this->files->copyDirectory($moduleVendorPath, tukecx_base_path($relativePath), null);

            modules_management()->enableModule(array_get($module, 'alias'));

            modules_management()->refreshComposerAutoload();

            $this->info("Module exported successfully.");
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
        }
    }
}
