<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


/**
 * @group  Transaction management
 *
 * APIs for Transaction
 */
class ArtisanCommandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       // \Artisan::call('migrate');
            dd('Command executed succefull');

        
    }

    

}
