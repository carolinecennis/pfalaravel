<?php
/**
 * Created by IntelliJ IDEA.
 * User: cennis
 * Date: 7/14/16
 * Time: 2:40 PM
 */

namespace App\Services;

use App\Services\MultiCurlService;
use App\Models\CleanTable;
use App\Models\MasterImport;
use App\Models\MasterTable;
use App\Models\DupTable;
use DB;

class PopulateTableService {

    public $pattern= '#[^A-Za-z0-9 ]#';
    public $cleanPattern = '/[^0-9]/';

    public function __construct($division)
    {
        $this->division = $division;

    }
    public function populateTables(){
       // $this->populateMaster();
        $this->multiPopulate();
    }

    public function multiPopulate()
    {
        $dataArray = array();
        $countImport = DB::table('MASTER_import')->count();
        //TODO make sure to catch the remainder on the offset and the offset is a whole number
        for ($i = 0; $i < 3; $i++) {
            $limit = $countImport / 3;
            $limit = ceil($limit);
            if ($i == 0) {
                $offset = 0;
            } else if($i == 2){
                $offset = $limit * 2;
                $limit = $countImport;
            }
            else {
                $offset = $limit * $i;
            }

            array_push($dataArray, ['limit' => $limit, 'offset' => $offset]);
        }

        $urls = array();

        foreach ($dataArray as $array) {
            $data = array(
                'url' => 'http://pfa-internal.dev/populateTables',
                'post' => array(
                    'offset' => $array['offset'],
                    'limit' => $array['limit'],
                    'division' => $this->division
                )
            );
            array_push($urls, $data);
        }
        $multicurl = new MultiCurlService($this->division);
        $multicurl->multiCurl($urls);
    }

    public function populateMaster($limit, $offset) {

        $fromImport = DB::table('MASTER_import')->select(
            'AmountPaid',
            'InvoiceAmount',
            'ShipmentNumber',
            'InvoiceNumber',
            'ShipDate',
            'BillOfLading',
            'CarrierName',
            'CheckNumber',
            'CheckDate',
            'RunNumber',
            'ShipperCity',
            'ShipperState',
            'ShipperName',
            'ConsigneeCity',
            'ConsigneeState',
            'ConsigneeName',
            'BatchNumber',
            'ActualWeight',
            'Location',
            'Link',
            'Division')->limit($limit)->offset($offset)->get();

        foreach ($fromImport as $import) {
            $masterTable = new MasterTable(['division' => $this->division]);
          //  $dupsTable = new DupTable(['division' => $this->division]);
            $cleanTable = new CleanTable(['division' => $this->division]);

            $masterTable->fill(array(
                'isDupRecord' => 0,
                'isSummedCheck' => 0,
                'isDupAmtPaid' => 0,
                'isDupShipment' => 0,
                'isDupBOL' => 0,
                'isDupInvoice' => 0,
                'dupCheck' => 0,
                'AmountPaid' => $import->AmountPaid,
                'OriginalAmtPaid' => $import->AmountPaid,
                'InvoiceAmount' => $import->InvoiceAmount,
                'ShipDate' => $import->ShipDate,
                'ShipmentNumber' => $this->cleanSymbol($import->ShipmentNumber, $this->pattern),
                'InvoiceNumber' => $this->cleanSymbol($import->InvoiceNumber, $this->pattern),
                'BillOfLading' => $this->cleanSymbol($import->BillOfLading, $this->pattern),
                'CarrierName' => $this->cleanSymbol($import->CarrierName, $this->pattern),
                'CheckNumber' => $import->CheckNumber,
                'CheckDate' => $import->CheckDate,
                'RunNumber' => $import->RunNumber,
                'ShipperCity' => $this->cleanSymbol($import->ShipperCity, $this->pattern),
                'ShipperState' => $import->ShipperState,
                'ShipperName' => $this->cleanSymbol($import->ShipperName, $this->pattern),
                'ConsigneeCity' => $this->cleanSymbol($import->ConsigneeCity, $this->pattern),
                'ConsigneeState' => $import->ConsigneeState,
                'ConsigneeName' => $this->cleanSymbol($import->ConsigneeName, $this->pattern),
                'BatchNumber' => $import->BatchNumber,
                'ActualWeight' => $import->ActualWeight,
                'Location' => $import->Location,
                'Link' => $import->Link,
                'Division' => $import->Division,
                'importDate' => date('Y-m-d')
            ));

            $concat = $import->CheckNumber . $import->BatchNumber . $import->ShipmentNumber . $import->InvoiceNumber . $import->CarrierName . $import->BillOfLading . $import->Division;
            $cleanShipment = rtrim($this->cleanSymbol($import->ShipmentNumber, $this->cleanPattern), 0);
            $cleanBOL = rtrim($this->cleanSymbol($import->BillOfLading, $this->cleanPattern), 0);
            $cleanInvoice = rtrim($this->cleanSymbol($import->InvoiceNumber, $this->cleanPattern), 0);

            $masterTable->save();
            $cleanTable->fill(array(
                'cleanShipment' => $cleanShipment,
                'cleanInvoice' => $cleanInvoice,
                'cleanBOL' => $cleanBOL,
                'cleanAmtPaid' => $import->AmountPaid,
                'division' => $import->Division,
                'importDate' => date('Y-m-d'),
                'dupCheck' => 0));
            $masterTable->clean(['division' => $this->division])->save($cleanTable);

        }
    }

    public function cleanSymbol($variable, $pattern)
    {
        $replace = '';
        $variable = preg_replace($pattern, $replace, $variable);
        return $variable;
    }


} 
