<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MakeFullResource extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:fullResource {name : Name resource to build} {table? : Name of table (plural) example convenios_obras}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crea el controlador de recurso, model, request, traits (AccessorsMutators), scope, seeder y factory';

    protected $paths = [];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $ds = DIRECTORY_SEPARATOR;
        $this->paths = [
            'stubs' => 'FullResource' . $ds,
            'model' => base_path() . $ds . 'app' . $ds . 'Models' . $ds,
            'request' => base_path() . $ds . 'app' . $ds . 'Http' . $ds . 'Requests' . $ds,
            'accessorsMutators' => base_path() . $ds . 'app' . $ds . 'Traits' . $ds . 'Models' . $ds . 'AccessorsMutators' . $ds,
            'booted' => base_path() . $ds . 'app' . $ds . 'Traits' . $ds . 'Models' . $ds . 'Booted' . $ds,
            'localScopes' => base_path() . $ds . 'app' . $ds . 'Traits' . $ds . 'Models' . $ds . 'LocalScopes' . $ds,
            'scope' => base_path() . $ds . 'app' . $ds . 'Scopes' . $ds,
            'controller' => base_path() . $ds . 'app' . $ds . 'Http' . $ds . 'Controllers' . $ds . 'Resources' . $ds,
            'seeder' => base_path() . $ds . 'database' . $ds . 'seeds' . $ds,
            'factory' => base_path() . $ds . 'database' . $ds . 'factories' . $ds,
        ];

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');

        $table = $this->argument('table');

        $fields = [];
        if ($table) {
            $fields = DB::select('describe ' . $table);

            if ($fields != '') {
                $fields = array_column($fields, 'Field');

                foreach ($fields as $key => $value) {
                    if (in_array($value, ['id', 'created_at', 'updated_at', 'deleted_at'])) {
                        unset($fields[$key]);
                    }
                }
            }
        }

        $this->info('Start');
        $this->makeModel($name, $fields);
        $this->makeRequest($name, 'Insert');
        $this->makeRequest($name, 'Update');
        $this->makeAccessorsMutators($name);
        $this->makeBooted($name);
        $this->makeLocalScopes($name);
        $this->makeScope($name);
        $this->makeController($name);
        $this->makeSeeder($name, $fields);
        $this->makeFactory($name, $fields);
    }


    public function makeModel($name, $fields = [])
    {
        $path = $this->paths['model'] . $name . ".php";

        if (file_exists($path)) {
            $this->error('Error creating Model ' . $name . ': File exists in ' . $path);
            return false;
        }

        $rules = '';

        if (count($fields) > 0) {
            $rules = "'" . implode('\' => [], \'', $fields) . '\' => []';
            $fields = "'" . implode("', '", $fields) . "'";
        } else {
            $fields = '';
        }

        $search = ['{{name}}', '{{fields}}', '{{rules}}'];
        $replace = [$name, $fields, $rules];

        $template = str_replace($search, $replace, $this->getStub($this->paths['stubs'] . 'model'));

        file_put_contents($path, $template);

        $this->info('Model ' . $name . ' created in ' . $path);
    }

    public function makeRequest($name, $action)
    {
        $path = $this->paths['request'] . $name . $action . "Request.php";

        if (file_exists($path)) {
            $this->error('Error creating Request ' . $name . $action . 'Request: File exists in ' . $path);
            return false;
        }

        $search = ['{{name}}', '{{action}}'];
        $replace = [$name, $action];

        $template = str_replace($search, $replace, $this->getStub($this->paths['stubs'] . 'request'));

        file_put_contents($path, $template);

        $this->info($name . $action . 'Request created in ' . $path);
    }

    public function makeScope($name)
    {
        $path = $this->paths['scope'] . $name . "Scope.php";

        if (file_exists($path)) {
            $this->error('Error creating Scope ' . $name . 'Scope: File exists in ' . $path);
            return false;
        }

        $search = ['{{name}}'];
        $replace = [$name];

        $template = str_replace($search, $replace, $this->getStub($this->paths['stubs'] . 'scope'));

        file_put_contents($path, $template);

        $this->info($name . 'Scope created in ' . $path);
    }

    public function makeAccessorsMutators($name)
    {
        $path = $this->paths['accessorsMutators'] . $name . ".php";

        if (file_exists($path)) {
            $this->error('Error creating Trait AccessorsMutators: File exists in ' . $path);
            return false;
        }

        $search = ['{{name}}'];
        $replace = [$name];

        $template = str_replace($search, $replace, $this->getStub($this->paths['stubs'] . 'accessors_mutators'));

        file_put_contents($path, $template);

        $this->info('Trait AccessorsMutators created in ' . $path);
    }

    public function makeBooted($name)
    {
        $path = $this->paths['booted'] . $name . ".php";

        if (file_exists($path)) {
            $this->error('Error creating Trait Booted: File exists in ' . $path);
            return false;
        }

        $search = ['{{name}}'];
        $replace = [$name];

        $template = str_replace($search, $replace, $this->getStub($this->paths['stubs'] . 'booted'));

        file_put_contents($path, $template);

        $this->info('Trait Booted created in ' . $path);
    }

    public function makeLocalScopes($name)
    {
        $path = $this->paths['localScopes'] . $name . ".php";

        if (file_exists($path)) {
            $this->error('Error creating Trait LocalScopes: File exists in ' . $path);
            return false;
        }

        $search = ['{{name}}'];
        $replace = [$name];

        $template = str_replace($search, $replace, $this->getStub($this->paths['stubs'] . 'local_scopes'));

        file_put_contents($path, $template);

        $this->info('Trait LocalScopes created in ' . $path);
    }

    public function makeController($name)
    {
        $path = $this->paths['controller'] . $name . "Controller.php";

        if (file_exists($path)) {
            $this->error('Error creating Controller ' . $name . 'Controller: File exists in ' . $path);
            return false;
        }

        $search = ['{{name}}', '{{nameParam}}'];
        $replace = [$name,  Str::camel($name)];

        $template = str_replace($search, $replace, $this->getStub($this->paths['stubs'] . 'controller'));

        file_put_contents($path, $template);

        $this->info($name . 'Controller created in ' . $path);
    }

    public function makeFactory($name, $fields = [])
    {
        $path = $this->paths['factory'] . $name . "Factory.php";

        if (file_exists($path)) {
            $this->error('Error creating Factory ' . $name . 'Factory: File exists in ' . $path);
            return false;
        }

        if (count($fields) > 0) {
            $fields = "'" . implode('\' => $faker->words, \'', $fields) . '\' => $faker->words';
        } else {
            $fields = '';
        }

        $search = ['{{name}}', '{{fields}}'];
        $replace = [$name, $fields];

        $template = str_replace($search, $replace, $this->getStub($this->paths['stubs'] . 'factory'));

        file_put_contents($path, $template);

        $this->info($name . 'Factory created in ' . $path);
    }

    public function makeSeeder($name, $fields = [])
    {
        $path = $this->paths['seeder'] . $name . "Seeder.php";

        if (file_exists($path)) {
            $this->error('Error creating Seeder ' . $name . 'Seeder: File exists in ' . $path);
            return false;
        }

        if (count($fields) > 0) {
            $fields = "'" . implode("' => null, '", $fields) . "' => null";
        } else {
            $fields = '';
        }

        $search = ['{{name}}', '{{fields}}'];
        $replace = [$name, $fields];

        $template = str_replace($search, $replace, $this->getStub($this->paths['stubs'] . 'seeder'));

        file_put_contents($path, $template);

        $this->info($name . 'Seeder created in ' . $path);
    }

    protected function getStub($type)
    {
        return file_get_contents(resource_path("stubs/$type.stub"));
    }
}
