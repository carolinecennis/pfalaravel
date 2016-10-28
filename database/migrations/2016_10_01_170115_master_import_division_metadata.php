<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MasterImportDivisionMetadata extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tablename = 'Division_Metadata';
        Schema::table($tablename, function ($table) {
            $table->string('fileHash');
        });

        $tablename = 'MASTER_import';
        Schema::table($tablename, function ($table) {
            $table->increments('id')->first();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
