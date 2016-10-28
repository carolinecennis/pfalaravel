<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColToDups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $divisions = App\Models\DivisionAll::lists('division', 'division');
        $indexName = 'concatCkBatchOptions';

        foreach ($divisions as $division) {
            $tablename = $division.'_Dups';
            Schema::table($tablename, function ($table) {
                $table->longText('concatCkBatchOptions');
            });

            DB::statement('CREATE INDEX ' . $indexName . ' ON ' . $tablename . ' (' . $indexName . '(200));');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $divisions = \App\Models\DivisionAll::all();

        foreach ($divisions as $division) {
            $table = new DupTable(['division' => $division]);
            $table['table'];
            Schema::table('users', function ($table) {
                $table->dropColumn('concatCkBatchOptions');
            });
        }
    }
}
