<?php

namespace App\Http\Controllers;

use Validator;
use App\History;
use App\Helpers\ZipToJson;
use Illuminate\Http\Request;
use App\Http\Requests\SendFormRequest;
use Illuminate\Support\Facades\Redirect;

class SendController extends Controller
{
	/**
     * Index page. Create new send form.
     *
     */
    public function create()
    {
        return view('send_form');
    }

    /**
     * Get valid form request, read zip-structure, encrypt to JSON,
     * echo result to user with JSON encoded option selector, sending JSON to Receiver API and 
     * getting response with success or failure.
     *
     */
    public function store(Request $request)
    {
    	$validator = Validator::make($request->all(), SendFormRequest::rules($request->get('type')));
    	if($validator->fails()){ 
    		return Redirect::route('send_form')->withErrors($validator)->withInput();
    	}

    	$json = $request->get('json');
    	if(is_null($json)){      	
    		//on first loading json field in request not setting  
    		//so we encode php array with structure to json 
    		//and add this string to hidden input
    		$filename = $request->file('zip')->getClientOriginalName();	
    		$structure = ZipToJson::read_zip($request->file('zip')->getpathName());
    		$json = json_encode($structure, ZipToJson::$all_opt); //by default setting all options
    	} else { 
    		//after pressing button "Apply" we go here, checking json transform options
    		//and set options to JSON
    		$filename = $request->get('filename');
    		if($request->get('options')){
    			$options = ZipToJson::set_options($request, $request->get('options'));
    			$json = json_encode(json_decode($json, true), $options);
    		}
    	}
    	$errors = [];
    	//final sending json to Receiver API
    	if($request->get('submit') == 'send'){ 
    		$response = json_decode($this->send($request, $json, $options, $filename), true);
    		if(isset($response['error'])){
    			$errors = $response;
    		}
    		if(isset($response['success'])) {
    			//if success setting success response with error container and redirecting to index page
    			return Redirect::route('send_form')->withErrors($response); 
    		}
    	}

    	$request->flash();
		return view('send_form')
			->with(['json' => $json,'filename' => $filename])
			->withErrors($errors);
    }

    /**
     * Sending JSON to Receiver API and returning JSON with success or error.
     *
     */
    public function send(Request $request, $json, $options, $filename)
    {
    	$temp = [
    		'json' 	   => json_decode($json, true),
    		'username' => $request->get('username'),
			'password' => $request->get('password'),
			'options'  => $options,
			'filename' => $filename,
    	];
    	$headers = [
			'Accept' => 'application/json',
			'Content-Type' => 'application/json',
		];
		$response = \Unirest\Request::post('http://receiver.api/json', $headers, json_encode($temp));
		return $response->raw_body;
    }

    /**
     * Return list of sended files.
     *
     */
    public function history(History $files)
    {
    	return view('history')->with('files', $files->paginate(10));
    }

}
