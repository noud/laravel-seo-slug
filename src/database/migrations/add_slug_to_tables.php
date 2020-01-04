<?php

namespace SEO;

use Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSlugToTables extends Migration
{
    protected $models = [];
    
    protected $slugColumnName;

    private function getTableClass(string $model)
    {
        return "App\\Models\\".$model;
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
        foreach ($this->models as $model) {
            $tableName = $this->getTableName($model);
            $slugColumnName = $this->getSlugColumnName($model);
            // drop slug
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn($slugColumnName);
            });
        }
    }
}
