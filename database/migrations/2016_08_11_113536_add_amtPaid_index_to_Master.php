<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAmtPaidIndexToMaster extends Migration
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
                $table->index(array('id', 'AmountPaid'));
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
