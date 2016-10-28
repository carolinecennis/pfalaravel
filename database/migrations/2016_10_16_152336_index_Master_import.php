<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IndexMasterImport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('MASTER_import', function ($table) {
            $table->index('ShipmentNumber');
        });

        $divisions = App\Models\DivisionAll::lists('division', 'division');

        foreach ($divisions as $division) {
            $tablename = $division.'_Master';
            Schema::table($tablename, function ($table) {
                $table->index('ShipmentNumber');
            });
        }
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
