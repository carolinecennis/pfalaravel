<?php
/**
 * Created by IntelliJ IDEA.
 * User: cennis
 * Date: 7/20/16
 * Time: 11:13 AM
 */

namespace App\Services;

use App\Models\MasterImport;
use App\Models\MasterTable;
use App\Models\CleanTable;
use App\Models\DupTable;
use App\Models\ReportTemp;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Classes\PHPExcel;
use DB;
use View;
use Log;
use Config;
use PDO;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Request;
use Yajra\Datatables\Datatables;

class ReportService extends Controller
{
    public $masterTable;
    public $cleanTable;
    public $dupTable;
    public $importTable;
    public $reportType;
    public $division;
    public $fileDate;
    public $reportFields;
    public $zeroReportFields;
    public $dupVariables;
    public $retrieveType;
    public $queryString;
    public $reportHeaders;
    public $zeroPaidHeaders;

    public function __construct($division, $reportType, $retrieveType)
    {
        $this->division = $division;
        $this->reportType = $reportType;
        $this->retrieveType = $retrieveType;
        $this->fileDate = (new \DateTime())->format('Y-m-d H.i.s');

        $masterTable = new MasterTable(['division' => $division]);
        $dupTable = new DupTable(['division' => $division]);
        $cleanTable = new CleanTable(['division' => $division]);

        $this->masterTable = $masterTable->getTable();
        $this->dupTable = $dupTable->getTable();
        $this->cleanTable = $cleanTable->getTable();
        $this->importTable = 'MASTER_import';

        $this->dupVariables = array('isDupShipment',
            'isDupInvoice',
            'isDupBOL',
            'isDupAmtPaid');

        $this->reportHeaders =  Config::get('settings.reportHeaders');
        $this->zeroPaidHeaders =  Config::get('settings.zeroPaidHeaders');

        $this->reportFields = array(
            $this->masterTable . '.id',
            $this->masterTable . '.dupCheck',
            $this->masterTable . '.isDupRecord',
            $this->masterTable . '.isSummedCheck',
            $this->masterTable . '.isDupAmtPaid',
            $this->masterTable . '.isDupShipment',
            $this->masterTable . '.isDupBOL',
            $this->masterTable . '.isDupInvoice',
            $this->masterTable . '.AmountPaid',
            $this->masterTable . '.OriginalAmtPaid',
            $this->masterTable . '.InvoiceAmount',
            $this->masterTable . '.ShipDate',
            $this->masterTable . '.ShipmentNumber',
            $this->cleanTable . '.cleanShipment',
            $this->masterTable . '.InvoiceNumber',
            $this->cleanTable . '.cleanInvoice',
            $this->masterTable . '.BillOfLading',
            $this->cleanTable . '.cleanBOL',
            $this->masterTable . '.CarrierName',
            $this->masterTable . '.CheckNumber',
            $this->masterTable . '.CheckDate',
            $this->masterTable . '.RunNumber',
            $this->masterTable . '.ShipperCity',
            $this->masterTable . '.ShipperState',
            $this->masterTable . '.ShipperName',
            $this->masterTable . '.ConsigneeCity',
            $this->masterTable . '.ConsigneeState',
            $this->masterTable . '.ConsigneeName',
            $this->masterTable . '.BatchNumber',
            $this->masterTable . '.ActualWeight',
            $this->masterTable . '.Location',
            $this->masterTable . '.Link',
            $this->masterTable . '.Division',
            $this->masterTable . '.importDate');

        $this->zeroReportFields = array(
            $this->masterTable . '.id',
            $this->importTable . '.AmountPaid as importAmtPaid',
            $this->masterTable . '.AmountPaid',
            $this->masterTable . '.OriginalAmtPaid',
            $this->importTable . '.InvoiceAmount as importInvoiceAmt',
            $this->masterTable . '.InvoiceAmount',
            $this->masterTable . '.ShipDate',
            $this->masterTable . '.ShipmentNumber',
            $this->masterTable . '.InvoiceNumber',
            $this->masterTable . '.BillOfLading',
            $this->masterTable . '.CarrierName',
            $this->masterTable . '.CheckNumber',
            $this->masterTable . '.CheckDate',
            $this->masterTable . '.RunNumber',
            $this->masterTable . '.ShipperCity',
            $this->masterTable . '.ShipperState',
            $this->masterTable . '.ShipperName',
            $this->masterTable . '.ConsigneeCity',
            $this->masterTable . '.ConsigneeState',
            $this->masterTable . '.ConsigneeName',
            $this->masterTable . '.BatchNumber',
            $this->masterTable . '.ActualWeight',
            $this->masterTable . '.Location',
            $this->masterTable . '.Link',
            $this->masterTable . '.Division',
            $this->masterTable . '.importDate');
    }


    public function getReports()
    {

        switch ($this->exportType) {
            case "export-to-excel" :
                $this->getCsvReports();
                exit();
            case "export-to-csv" :
                $this->getExcelReports();
                exit();
            default;
                break;
        }
    }

    public function getCsvReports()
    {
        $filename = "$this->reportType._$this->division.$this->fileDate.csv";

        $headers = [
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0'
            , 'Content-type' => 'text/csv'
            , 'Content-Disposition' => 'attachment; filename=' . $filename
            , 'Expires' => '0'
            , 'Pragma' => 'public'
        ];

        $list = ReportTemp::all()->toArray();

        # add headers for each column in the CSV download
        array_unshift($list, array_keys($list[0]));

        $callback = function () use ($list) {
            $FH = fopen('php://output', 'w');
            foreach ($list as $row) {
                fputcsv($FH, $row);
            }
            fclose($FH);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function getExcelReports()
    {

        $filename = "$this->reportType._$this->division.$this->fileDate.xls";

        $headers = [
            'Content-type' => 'application/vnd.ms-excel'
            , 'Content-Disposition' => 'attachment; filename=' . $filename
            , 'Expires' => '0'
            , 'Pragma' => 'public'
        ];

        //get data from temp

        # add headers for each column in the CSV download
        array_unshift($list, array_keys($list[0]));

        $callback = function () use ($list) {
            $FH = fopen('php://output', 'w');
            foreach ($list as $row) {
                fputcsv($FH, $row);
            }
            fclose($FH);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function getReportType()
    {
        $createTableService = new CreateTableService($this->division);
        $createTableService->dropReportTemp();

        switch ($this->reportType) {
            case "possibleDups":
                $this->getPossibleDupsReportData();
                break;
            case "allData":
                $this->getAllDataReportData();
                break;
            case "summedChecks":
                $this->getSummedCheckData();
                break;
            case "zeroAmtPaid":
                $this->getZeroPaidData();
                break;
            default;
        }
    }

    public function getZeroPaidData(){

        $importTable = $this->importTable;
        $masterTable = $this->masterTable;
        $zeroReportFields = implode(",",$this->zeroReportFields);

        $zeroPaid = "CREATE TABLE ReportTemp as 
        SELECT distinct $zeroReportFields 
        FROM $masterTable $masterTable
        JOIN $importTable $importTable on $importTable.`ShipmentNumber` = $masterTable.`ShipmentNumber`
        where $importTable.InvoiceAmount < $masterTable.AmountPaid
        and master.dupCheck = 0";

        DB::statement($zeroPaid);

        $this->createExcelReport();
    }

    public function getPossibleDupsReportData()
    {
        $cleanTable = $this->cleanTable;
        $masterTable = $this->masterTable;
        $reportFields = implode(",",$this->reportFields);
        $dupsQuery = "CREATE TABLE ReportTemp as
             SELECT DISTINCT
              $reportFields 
              FROM $cleanTable $cleanTable 
              JOIN $masterTable $masterTable on
              $masterTable.id = $cleanTable.id
             WHERE $masterTable.isDupShipment = 1

            UNION

            SELECT DISTINCT 
              $reportFields
              FROM $cleanTable $cleanTable  
              JOIN $masterTable $masterTable on
              $masterTable.id = $cleanTable.id
             WHERE $masterTable.isDupInvoice = 1

            UNION

            SELECT DISTINCT 
              $reportFields
              FROM $cleanTable $cleanTable  
              JOIN $masterTable $masterTable on
              $masterTable.id = $cleanTable.id
             WHERE $masterTable.isDupBOL = 1

            UNION

            SELECT DISTINCT 
              $reportFields
              FROM $cleanTable $cleanTable  
              JOIN $masterTable $masterTable on
              $masterTable.id = $cleanTable.id
             WHERE $masterTable.isDupAmtPaid = 1";
            
            DB::statement($dupsQuery);
        
            //Log::info("dupsQuery= ", $dupsQuery);

            if($this->retrieveType == 'Download Reports') {
                $this->createExcelReport();
            } else {
                return true;
            }
    }

    public function getAllDataReportData()
    {
        $cleanTable = $this->cleanTable;
        $masterTable = $this->masterTable;
        $reportFields = implode(",",$this->reportFields);

        $allDataQuery= "CREATE TABLE ReportTemp as
                SELECT $reportFields
                from $masterTable $masterTable
                JOIN $cleanTable $cleanTable on $cleanTable.id = $masterTable.id
                WHERE $masterTable.dupCheck != 1
                and $masterTable.AmountPaid != 0";

        DB::statement($allDataQuery);

        $this->createExcelReport();
    }

    public function getSummedCheckData()
    {
        $dupsTable = $this->dupTable;
        $masterTable = $this->masterTable;
        $cleanTable = $this->cleanTable;
        $reportFields = implode(",",$this->reportFields);

        DB::statement('CREATE TABLE ReportTemp as
                SELECT '.$reportFields.'
                from '.$masterTable.' '.$masterTable.'
                JOIN '.$dupsTable.' '.$dupsTable.' on '.$dupsTable.'.id = '.$masterTable.'.id
                JOIN '.$cleanTable.' '.$cleanTable.' on '.$cleanTable.'.id = '.$masterTable.'.id
                WHERE '.$masterTable.'.isSummedCheck = 1
                and '.$masterTable.'.AmountPaid != 0
                order by '.$dupsTable.'.concatCkBatch');

        $this->createExcelReport();
    }


    public function createExcelReport()
    {
        $headers = ($this->reportType == 'zeroAmtPaid') ? $this->zeroPaidHeaders : $this->reportHeaders;
        $filename = "$this->reportType._$this->division.$this->fileDate.csv";
        $fh = fopen('php://output', 'w');
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Expires: 0");

        fputcsv($fh,
            $headers
        );
        //TODO: refactor this a little cleaner
        $pdo = \DB::connection()->getPdo();
        $count = DB::table('ReportTemp')->count();
        if ($count > 100000) {
            $limit = 100000;
            $loop = $count / 100000;
            $loop = ceil($loop);

            for ($i = 0; $i < $loop; $i++) {
                if ($i == 0) {
                    $offset = 0;
                    $fh = fopen('php://output', 'w');
                } else {
                    $offset = $limit * $i;
                    $fh = fopen('php://output', 'a');
                }
                $query = "SELECT * FROM ReportTemp limit $limit offset $offset";

                $stmt = $pdo->prepare($query);
                $stmt->execute();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    fputcsv($fh, $row);
                }
                fclose($fh);
            }
        } else {
            $query = "SELECT * FROM ReportTemp";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                fputcsv($fh, $row);
            }
            fclose($fh);
        }
    }

    public function index(ReportDataTable $dataTable)
    {
        $dataTable->query($this->queryString);
        return $dataTable->render('export');
    }

}
