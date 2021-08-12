<?php

namespace eloquentFilter\Command;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

/**
 * Class MakeEloquentFilter.
 */
class MakeEloquentFilter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eloquentFilter:filter {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '
███████╗██╗░░░░░░█████╗░░██████╗░██╗░░░██╗███████╗███╗░░██╗████████╗  ███████╗██╗██╗░░░░░████████╗███████╗██████╗░
██╔════╝██║░░░░░██╔══██╗██╔═══██╗██║░░░██║██╔════╝████╗░██║╚══██╔══╝  ██╔════╝██║██║░░░░░╚══██╔══╝██╔════╝██╔══██╗
█████╗░░██║░░░░░██║░░██║██║██╗██║██║░░░██║█████╗░░██╔██╗██║░░░██║░░░  █████╗░░██║██║░░░░░░░░██║░░░█████╗░░██████╔╝
██╔══╝░░██║░░░░░██║░░██║╚██████╔╝██║░░░██║██╔══╝░░██║╚████║░░░██║░░░  ██╔══╝░░██║██║░░░░░░░░██║░░░██╔══╝░░██╔══██╗
███████╗███████╗╚█████╔╝░╚═██╔═╝░╚██████╔╝███████╗██║░╚███║░░░██║░░░  ██║░░░░░██║███████╗░░░██║░░░███████╗██║░░██║
╚══════╝╚══════╝░╚════╝░░░░╚═╝░░░░╚═════╝░╚══════╝╚═╝░░╚══╝░░░╚═╝░░░  ╚═╝░░░░░╚═╝╚══════╝░░░╚═╝░░░╚══════╝╚═╝░░╚═╝

    Create A New Eloquent Custom Model Filter';

    /**
     * Class to create.
     *
     * @var array|string
     */
    protected $class;

    /**
     * The filesystem instance.
     *
     * @var Filesystem
     */
    protected $files;

    /**
     * MakeEloquentFilter constructor.
     *
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     *
     * @return mixed
     */
    public function handle()
    {
        $this->makeClassName()->compileStub();
        $this->info(class_basename($this->getClassName()).' Created Successfully!');
    }

    /**
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function compileStub()
    {
        if ($this->files->exists($path = $this->getPath())) {
            $this->error("\n\n\t".$path.' Already Exists!'."\n");
            exit;
        }
        $this->makeDirectory($path);

        $stubPath = __DIR__.'/modelfilter.stub';

        if (!$this->files->exists($stubPath) || !is_readable($stubPath)) {
            $this->error(sprintf('File "%s" does not exist or is unreadable.', $stubPath));
            exit;
        }

        $tmp = $this->applyValuesToStub($this->files->get($stubPath));
        $this->files->put($path, $tmp);
    }

    /**
     * @param $stub
     *
     * @return string|string[]
     */
    public function applyValuesToStub($stub)
    {
        $className = $this->getClassBasename($this->getClassName());
        $search = ['{{class}}', '{{namespace}}'];
        $replace = [$className, str_replace('\\'.$className, '', $this->getClassName())];

        return str_replace($search, $replace, $stub);
    }

    /**
     * @param $class
     *
     * @return string
     */
    private function getClassBasename($class)
    {
        $class = is_object($class) ? get_class($class) : $class;

        return basename(str_replace('\\', '/', $class));
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->laravel->path.DIRECTORY_SEPARATOR.$this->getFileName();
    }

    /**
     * @return string|string[]
     */
    public function getFileName()
    {
        return str_replace([$this->getAppNamespace(), '\\'], ['', DIRECTORY_SEPARATOR], $this->getClassName().'.php');
    }

    /**
     * @return string
     */
    public function getAppNamespace()
    {
        return $this->laravel->getNamespace();
    }

    /**
     * Build the directory for the class if necessary.
     *
     * @param string $path
     *
     * @return string
     */
    protected function makeDirectory($path)
    {
        if (!$this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }
    }

    /**
     * Create Filter Class Name.
     *
     * @return $this
     */
    public function makeClassName()
    {
        $parts = array_map([Str::class, 'studly'], explode('\\', $this->argument('name')));
        $className = array_pop($parts);
        $ns = count($parts) > 0 ? implode('\\', $parts).'\\' : '';

        $fqClass = config('eloquentFilter.namespace', 'App\\ModelFilters\\').$ns.$className;

        if (substr($fqClass, -6, 6) !== 'Filter') {
            $fqClass .= 'Filter';
        }

        if (class_exists($fqClass)) {
            $this->error("\n\n\t$fqClass Already Exists!\n");
            exit;
        }

        $this->setClassName($fqClass);

        return $this;
    }

    /**
     * @param $name
     *
     * @return $this
     */
    public function setClassName($name)
    {
        $this->class = $name;

        return $this;
    }

    /**
     * @return array|string
     */
    public function getClassName()
    {
        return $this->class;
    }
}
