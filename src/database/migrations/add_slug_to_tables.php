<?php

namespace SEO;

use Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSlugToTables extends Migration
{
    const MODELS_NAMESPACE = 'App\Models\\';

    const MODEL_METHOD = 'generateSlug';

    protected $models = [];
    
    protected $slugColumnName;

    /**
     * @param $dir
     */
    function getClassesList($dir)
    {
        $classes = [];
        $classFiles = \File::allFiles($dir);
        foreach ($classFiles as $class) {
            // skip most common model dirs
            if ((strpos($class->getRealPath(), 'Base') === false) &&
            (strpos($class->getRealPath(), 'Traits') === false) &&
            (strpos($class->getRealPath(), 'User') === false)) {
                $class->classname = str_replace(
                    [app_path(), '/', '.php'],
                    ['App', '\\', ''],
                    $class->getRealPath()
                );
                $className = $class->classname;
                $instance = new $className();
                $classes[] = $instance;
            }
        }
        return $classes;
    }

    private function getModelClasses()
    {
        return $this->getClassesList(app_path('Models'));
    }

    private function getClassNamesWithMethod(string $methodName)
    {
        $classNames = [];
        foreach($this->getModelClasses() as $model) {
            if(method_exists($model, $methodName)){
                $classNames[] = class_basename($model);
            }
        }
        return $classNames;
    }

    private function getTableClass(string $model)
    {
        return self::MODELS_NAMESPACE . $model;
    }

    private function getTableName(string $model)
    {
        $modelClass = $this->getTableClass($model);
        return (new $modelClass())->getTable();
    }

    private function getSlugColumnName(string $model)
    {
        $modelClass = $this->getTableClass($model);
        return (new $modelClass())->getRouteKeyName();
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->models = $this->getClassNamesWithMethod(self::MODEL_METHOD);
        foreach ($this->models as $model) {
            $tableClass = $this->getTableClass($model);
            $tableName = $this->getTableName($model);
            $this->slugColumnName = $this->getSlugColumnName($model);
            // add slug
            Schema::table($tableName, function (Blueprint $table) {
                $table->string($this->slugColumnName)->nullable()->after('updated_at');
            });
            // fill slug
            $tupels = $tableClass::all();
            foreach ($tupels as $tupel) {
                $slugColumnName = $this->slugColumnName;
                $tupel->$slugColumnName = $tupel->generateSlug();
                $tupel->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->models = $this->getClassNamesWithMethod(self::MODEL_METHOD);
        foreach ($this->models as $model) {
            $tableName = $this->getTableName($model);
            $this->slugColumnName = $this->getSlugColumnName($model);
            // drop slug
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn($this->slugColumnName);
            });
        }
    }
}
