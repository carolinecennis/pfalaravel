<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMultiindex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $divisions = App\Models\DivisionAll::lists('division', 'division');

        foreach ($divisions as $division) {
            $tablename = $division.'_Master';
            Schema::table($tablename, function ($table) {
                $table->index(['CheckNumber',
                    'BatchNumber',
                    'ShipmentNumber',
                    'InvoiceNumber',
                    'CarrierName',
                    'BillOfLading',
                    'Division'],'multiIndex');
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
