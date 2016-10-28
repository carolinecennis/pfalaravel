<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\ReportDataTable;


use App\Http\Requests;

class ExportController extends Controller
{
    public function index(ReportDataTable $dataTable)
    {
        return $dataTable->render('export');
    }
}
