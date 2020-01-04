<?php

namespace SEO;

use Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSlugToTables extends Migration
{
    const SLUG_COLUMN_NAME = 'slug';

    protected $models = [];
    
    private function getTableClass(string $model)
    {
        return "App\\Models\\".$model;
    }    

    private function getTableName(string $model)
    {
        $modelClass = $this->getTableClass($model);
        return (new $modelClass())->getTable();
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
            // add slug
            Schema::table($tableName, function (Blueprint $table) {
                $table->string(self::SLUG_COLUMN_NAME)->nullable()->after('updated_at');
            });
            // fill slug
            $tupels = $tableClass::all();
            foreach ($tupels as $tupel) {
                $tupel->slug = $tupel->generateSlug();
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
            // drop slug
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn(self::SLUG_COLUMN_NAME);
            });
        }
    }
}
