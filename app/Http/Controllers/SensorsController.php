<?php

namespace App\Http\Controllers;

use \App\Sensor;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class SensorsController extends Controller
{ 
	//fetches sensor data from the database
    public function index()
    {
	    $ldr_data = $this->ldr(true);
	    return view('welcome', compact('ldr_data'));
    }
    
    public function ldr($returnPhpObject = false)
    {
	    $ldr_data = Sensor::orderBy('created_at', 'desc')->get();
	    if ($returnPhpObject == true)
	    	return $ldr_data;
	    else
	    	return response()->json($ldr_data);
    }
    
    //logs sensor data to the database via Ethernet shield on Arduino posting to this URL - untested
    public function logData(Request $request)
    {
	    try{
		    
		    $this->validate($request, ['luminosity' => 'required']);
		    $sData = new Sensor;
		    $sData->luminosity = $request->get('luminosity');
		    $sData->save();
		    
	    }catch(QueryException $e)
	    {
		    return view()->make("Unable to log LDR sensor data. See ->", $e);
	    }
	    return redirect()->to('/');
    }
    
}
