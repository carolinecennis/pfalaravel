<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use App\Services\DivisionService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\UploadService;
use App\Services\ZeroPaidService;
use App\Models\MasterImport;
use Input;
use Config;

/**
 * Class ZeroPaidController
 *
 * @package \App\Http\Controllers
 */
class ZeroPaidController extends Controller
{

    public function index()
    {
        return view('zero');
    }

    public function upload(Request $request)
    {
        $file = Input::file('upload_file');

        $uploadService = new UploadService();
        $processed = $uploadService->findColumns($file);
        $divisionService = new DivisionService();
        $division = $divisionService->getImportDivision();

        if ($division != "") {
            if ($processed) {
                //use reportService to find all where server invoice amt > file invoice amt and shipment # matches
                $reportService = new ReportService($division, 'zeroAmtPaid', 'download');
                $reportService->getReportType();
            } else {
                return view('home', ['message' => 'Records could not be processed']);
            }
        } else {
            return view('home', ['message' => 'Division cannot be blank, check import file']);
        }
    }
}
