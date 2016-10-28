<?php
/**
 * Created by IntelliJ IDEA.
 * User: cennis
 * Date: 7/17/16
 * Time: 10:43 PM
 */

namespace App\Http\Controllers;

use App\Models\DivisionMetadata;
use App\Services\DivisionService;
use DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\View;
use App\Http\Controllers\Response;


class DeleteController extends Controller {

    public $divisionService;

    public function __construct()
    {
      $divisionService = new DivisionService();
        $this->divisionService = $divisionService;
    }

    public function deleteMain() {

        $divisions = $this->divisionService->getDivisions();
        $divisions->prepend('Choose Division');

        return view('delete', compact('divisions'));
    }

    public function delete(Request $request){
        $inputArray = $request->all();
        $division = $inputArray['division'];
        $importDate = $inputArray['importDate'];
        $table = $division."_Master";

        $toDelete = array(['division', $division], ['importDate', $importDate]);

        $affected = DB::table($table)->where($toDelete)->delete();
        DivisionMetadata::where($toDelete)->delete();

        $divisions = $this->divisionService->getDivisions();
        $divisions->prepend('Choose Division');

        return view('delete', compact('divisions'), ['count' => $affected]);

    }

    public function getDates($division) {

        $dates = DB::table('Division_Metadata')->select('importDate')
            ->where('division', $division)
            ->orderBy('importDate', 'desc')->get();

        return $dates;
    }

    public function getRunnumber($division) {

        $table = $division."_Master";

        $runnumber = DB::table($table)
            ->distinct()
            ->select('runnumber')
            ->where('division', $division)
            ->whereNotNull('runnumber')
            ->get();

        return $runnumber;
    }

}
