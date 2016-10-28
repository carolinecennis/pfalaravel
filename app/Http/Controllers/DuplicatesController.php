<?php
/**
 * Created by IntelliJ IDEA.
 * User: cennis
 * Date: 7/10/16
 * Time: 9:59 AM
 */

namespace App\Http\Controllers;

use App\Models\MasterImport;
use App\Services\DivisionService;
use App\Services\DuplicateService;
use App\Services\SumChecksService;
use DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Services\CreateTableService;
use App\Services\PopulateTableService;
use Illuminate\Http\Response;
use Log;

class DuplicatesController extends Controller {

    public $divisionService;

    public function __construct()
    {
        $divisionService = new DivisionService();
        $this->divisionService = $divisionService;
    }

    public function processDuplicates(Request $request) {

        $inputArray = $request->all();
            $division = $inputArray['division'];
            $beginrunnumber = "";
            $date = $inputArray['date'];
            $groupBy = array();

        if($request->has('shipperCity'))
        {
            $shipperCity = $inputArray['shipperCity'];
            array_push($groupBy, 'ShipperCity');
        }

        if($request->has('runNumber'))
        {
            $runNumber = $inputArray['runNumber'];
            array_push($groupBy, 'RunNumber');
        }

        if($request->has('beginrunnumber'))
        {
            $beginrunnumber = $inputArray['beginrunnumber'];
        }

        $sumChecksService = new SumChecksService($division);
        Log::info('summing...');
        $sumChecksService->resetAll();
        $sumChecksService->sumLikeChecks($groupBy, $beginrunnumber);

        $duplicateService = new DuplicateService($division, $groupBy, $date);
        $duplicateService->getDuplicates($beginrunnumber);

        $affected = $duplicateService->getAnalyzeCount();

        return view('run', ['count' => $affected,'division' => $division, 'date' => $date ]);
    }

    public function run(){

        $divisions = $this->divisionService->getDivisions();
        $divisions->prepend('Choose Division');

        return view('run', compact('divisions'));

    }

    public function updateDuplicates(Request $request){

        //this route will be hit multiple times to process these queries simultaneously

        $inputArray = $request->all();
        $division = $inputArray['division'];
        $key = $inputArray['key'];
        $value = $inputArray['value'];
        $date = $inputArray['date'];

        if($request->has('groupBy')) {
            $groupBy = $inputArray['groupBy'];
            $groupBy = explode(",", $groupBy);
        } else{
            $groupBy = null;
        }

        $duplicateService = new DuplicateService($division, $groupBy, $date);
        $query = $duplicateService->findDups($key,$value);

        // return Response($query);

    }

    public function sumDuplicates(Request $request){

        //this route will be hit multiple times to process these queries simultaneously

        $inputArray = $request->all();
        $division = $inputArray['division'];
        $key = $inputArray['key'];
        $value = $inputArray['value'];

        $sumCheckService = new SumChecksService($division);
        $sumCheckService->sumDuplicates($key,$value);


    }

}
