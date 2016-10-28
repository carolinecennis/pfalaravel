<?php
/**
 * Created by IntelliJ IDEA.
 * User: cennis
 * Date: 7/19/16
 * Time: 4:26 PM
 */

namespace App\Http\Controllers;

use Datatables;
use App\Models\DivisionMetadata;
use App\Models\ReportTemp;
use App\Services\ReportService;
use DB;
use Excel;
use Maatwebsite\Excel\Classes\PHPExcel;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\View;
use App\Http\Controllers\Response;
use App\Services\DivisionService;
use App\Services\CreateTableService;
use App\Services\ExportService;
use Yajra\Datatables\Services\DataTable;


class ReportController extends Controller
{

    public $divisionService;

    public function __construct()
    {
        $divisionService = new DivisionService();
        $this->divisionService = $divisionService;

    }

    public function reportsHome()
    {

        $reportTypes = [
            'Choose Report Type' => 'Choose Report Type',
            'possibleDups' => 'Possible Duplicates',
            'allData' => 'All Data',
            'summedChecks' => 'Summed Checks'
        ];
        $divisions = $this->divisionService->getDivisions();
        $divisions->prepend('Choose Division');

        return view('reports', compact('divisions'), compact('reportTypes'));
    }

    public function downloadReports(Request $request)
    {
        $inputArray = $request->all();
        $division = $inputArray['division'];
       // $importDate = $inputArray['date'];
        $reportType = $inputArray['reportType'];
        $retrieveType = $inputArray['retrieveType'];

        $reportService = new ReportService($division, $reportType, $retrieveType);
        $view = $reportService->getReportType();
        if($retrieveType == 'View Reports') {
            return redirect('datatables');
        }
    }

    /**
     * Displays datatables front end view
     *
     * @return \Illuminate\View\View
     */
    public function getIndex()
    {
        return view('export');
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function anyData()
    {
       $query = DB::table('ReportTemp')->select('id', 'AmountPaid', 'OriginalAmtPaid');

        return Datatables::of($query)->make(true);
    }
}