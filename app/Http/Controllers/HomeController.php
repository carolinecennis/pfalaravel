<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\NestingService;

/**
 * Class HomeController
 *
 * @package App\Http\Controllers
 */
class HomeController extends Controller {

    /**
     * Home Page
     *
     * @param Request $request
     *
     * @return string
     */
    public function index (Request $request) {


        return view('home');
    }
}