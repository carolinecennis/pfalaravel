<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\MasterImport;
use App\Services\UploadService;
use App\Services\DivisionService;
use App\Services\PopulateTableService;
use App\Services\CreateTableService;
use App\Models\DivisionAll;
use Input;
use Validator;
use Illuminate\Http\Request;
use Response;
use Session;
use Redirect;
use Auth;
use Config;
use DB;
use Excel;

class UploadController extends Controller
{
    public $divisionService;


    public function __construct()
    {
        $divisionService = new DivisionService();
        $this->divisionService = $divisionService;
    }

    public function index(Request $request)
    {

        return view('upload');
    }

    public function upload(Request $request)
    {

        $inputArray = $request->all();
        $division = $inputArray['division'];
        $date = date('Y-m-d');

        // getting all of the post data
        $file = Input::file('upload_file');
        $fileHash = md5_file($file);

            $uploadService = new UploadService();
            $processed = $uploadService->findColumns($file);

            if($processed == "true") {

                DB::table('MASTER_import')->update(['Division' => DB::raw('UPPER(replace(Division, \'\',"\_"))')]);

                $currDivResult = DB::table('MASTER_import')->select('Division')->take(1)->first();
                $currDiv = $currDivResult->Division;

                $divResult = DivisionAll::where('division', '=', $currDiv)->get();
                if($divResult->isEmpty()){

                    $createTableService = new CreateTableService($currDiv);
                    $createTableService->createTables();
                    $divisionAll = new DivisionAll;
                    $divisionAll->fill(array(
                        'division' => $currDiv
                    ));
                    $divisionAll->save();
                }
                //has this dataset (division + date) already been uploaded?
                $repeat = $this->divisionService->updateDivisionMetadata($fileHash);

                if($currDiv != strtoupper($division)){
                    $processed = 'Division entered does not match division in file';
                    return view('home', ['message' => $processed]);
                } else if($repeat != true){
                    $populateTableService = new PopulateTableService($division);
                    $populateTableService->populateTables();
                    return view('run', ['division' => $division, 'date' => $date]);
                } else {
                    $processed = $file->getClientOriginalName().' has already been processed';
                    return view('home', ['message' => $processed]);
                }
            } else {
                return view('home', ['message' => $processed]);
            }
    }

    public function populateTables(Request $request){
        $inputArray = $request->all();

        $division = $inputArray['division'];

        $populateTableService = new PopulateTableService($division);

        $offset = $inputArray['offset'];
        $limit = $inputArray['limit'];

        $populateTableService->populateMaster($limit, $offset);
    }
}
