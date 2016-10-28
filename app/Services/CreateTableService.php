<?php
/**
 * Created by IntelliJ IDEA.
 * User: cennis
 * Date: 7/13/16
 * Time: 7:51 AM
 */

namespace App\Services;

use Config;
use DB;
use Schema;
use Illuminate\Database\Schema\Blueprint;


class CreateTableService
{

    private $division;

    public function __construct($division)
    {
        $this->division = $division;
    }

    public function createTables()
    {
        $this->createMaster();
        $this->createClean();
      //  $this->createDups();
    }

    public function createMaster()
    {

        $tablename = $this->division . '_Master';

        Schema::create($tablename, function ($table) {
            $table->increments('id');
            $table->boolean('isDupRecord');
            $table->boolean('isSummedCheck');
            $table->boolean('isDupAmtPaid');
            $table->boolean('isDupShipment');
            $table->boolean('isDupBOL');
            $table->boolean('isDupInvoice');
            $table->boolean('dupCheck');
            $table->decimal('AmountPaid', 20, 2);
            $table->decimal('OriginalAmtPaid', 20, 2);
            $table->decimal('InvoiceAmount', 20, 2);
            $table->string('ShipmentNumber');
            $table->string('InvoiceNumber');
            $table->string('ShipDate');
            $table->string('BillOfLading');
            $table->string('CarrierName');
            $table->string('CheckNumber');
            $table->string('CheckDate');
            $table->string('RunNumber');
            $table->string('ShipperCity');
            $table->string('ShipperState');
            $table->string('ShipperName');
            $table->string('ConsigneeCity');
            $table->string('ConsigneeState');
            $table->string('ConsigneeName');
            $table->string('BatchNumber');
            $table->string('ActualWeight');
            $table->string('Location');
            $table->string('Link');
            $table->string('Division');
            $table->date('importDate');
        });

        Schema::table($tablename, function ($table) {
            $table->index(array('id', 'AmountPaid'));
            $table->index('Division');
        });
    }

    public function createClean()
    {

        $tablename = $this->division . '_CleanData';

        Schema::create($tablename, function ($table) {
            $table->increments('id');
           // $table->integer('master_table_id')->unsigned();
            $table->string('cleanShipment');
            $table->string('cleanInvoice');
            $table->string('cleanBOL');
            $table->decimal('cleanAmtPaid', 20, 2);
            $table->string('division');
            $table->date('importDate');
            $table->boolean('dupCheck');
            $table->foreign('id')->references('id')->on($this->division . '_MASTER')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::table($tablename, function ($table) {
            $table->index('cleanShipment');
            $table->index('cleanInvoice');
            $table->index('cleanBOL');
            $table->index('cleanAmtPaid');
        });
    }

    public function createReportTemp()
    {
        if (!Schema::hasTable('ReportTemp')) {
            Schema::create('ReportTemp', function ($table) {
                $table->integer('id');
                $table->boolean('dupCheck');
                $table->boolean('isDupRecord');
                $table->boolean('isSummedCheck');
                $table->boolean('isDupAmtPaid');
                $table->boolean('isDupShipment');
                $table->boolean('isDupBOL');
                $table->boolean('isDupInvoice');
                $table->decimal('AmountPaid', 20, 2);
                $table->decimal('OriginalAmtPaid', 20, 2);
                $table->decimal('InvoiceAmount', 20, 2);
                $table->string('ShipDate');
                $table->string('ShipmentNumber');
                $table->string('cleanShipment');
                $table->string('InvoiceNumber');
                $table->string('cleanInvoice');
                $table->string('BillOfLading');
                $table->string('cleanBOL');
                $table->string('CarrierName');
                $table->string('CheckNumber');
                $table->string('CheckDate');
                $table->string('RunNumber');
                $table->string('ShipperCity');
                $table->string('ShipperState');
                $table->string('ShipperName');
                $table->string('ConsigneeCity');
                $table->string('ConsigneeState');
                $table->string('ConsigneeName');
                $table->string('BatchNumber');
                $table->string('ActualWeight');
                $table->string('Location');
                $table->string('Link');
                $table->string('Division');
                $table->date('importDate');
            });
        }
    }

    public function dropReportTemp()
    {
        if (Schema::hasTable('ReportTemp')) {
            Schema::drop('ReportTemp');
        }
    }

    public function addIndex($tablename, $indexName)
    {
       DB::statement('CREATE INDEX '.$indexName.' ON '.$tablename.' ('.$indexName.'(200));');

    }

    public function dropIndex($tablename, $indexName)
    {
            Schema::table($tablename, function ($table) use ($indexName) {
                $table->dropIndex($indexName);
            });
    }

} 
