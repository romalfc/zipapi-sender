<?php

namespace App\Http\Controllers;

use Validator;
use App\History;
use App\Helpers\ZipToJson;
use App\Helpers\Encryption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Http\Requests\SendFormRequest;
use Illuminate\Support\Facades\Redirect;

class SendController extends Controller
{
	/**
     * Index page. Create new send form.
     * 
     * @return HTML page
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
     * @param  object $request Request object 
     * @return HTML page
     */
    public function store(Request $request)
    {
    	$validator = Validator::make($request->all(), SendFormRequest::rules($request->get('type')));
    	if($validator->fails()){ 
    		return Redirect::route('send_form')->withErrors($validator)->withInput();
    	}

    	$json = $request->get('json');
        $json_e = $request->get('json_e');
        $ssl_method = Encryption::$ssl_methods[0];
        if(isset(Encryption::$ssl_methods[$request->get('e_method')])){
            $ssl_method = Encryption::$ssl_methods[$request->get('e_method')];
        }
    	if(is_null($json)){      	
    		//on first loading json field in request not setting  
    		//so we encode php array with structure to json 
    		//and add this string to hidden input
    		$filename = $request->file('zip')->getClientOriginalName();	
    		$array = ZipToJson::read_zip($request->file('zip')->getpathName(), $filename, $ssl_method);
            $structure = $array['structure'];
            $structure_e = $array['encrypted']; //encrypted array with temp_id from temp table
    		$json = json_encode($structure, ZipToJson::$all_opt); //by default setting all options
            $json_e = json_encode($structure_e, ZipToJson::$all_opt);
    	} 
        else { 
    		$filename = $request->get('filename');
    		if($request->get('options')){
    			$options = ZipToJson::set_options($request, $request->get('options'));
    			$json = json_encode(json_decode($json, true), $options);
                $json_e = json_encode(json_decode($json_e, true), $options);
    		}
    	}
    	$errors = [];
    	//final sending json to Receiver API
    	if($request->get('submit') == 'send'){ 
    		$response = json_decode($this->send($request, $json_e, $options), true);
            //$response = $this->send($request, $json_e, $options, $filename); 
    		if(isset($response['error']))  $errors = $response;
    		if(isset($response['success'])) {
    			//if success setting success response with error container and redirecting to index page
    			return Redirect::route('send_form')->withErrors($response); 
    		}
    	}

    	$request->flash();
		return view('send_form')
			->with([
                    'json'     => $json,
                    'json_e'   => $json_e,
                    'filename' => $filename
            ])
			->withErrors($errors);
    }

    /**
     * Sending JSON to Receiver API and returning JSON with success or error.
     * 
     * @param  object      $request Request object 
     * @param  json_string $json_e File structure transformed to JSON and encoded.
     * @param  numeric     $options PHP flag for jsosn_encode() transformation
     * @return json_string
     */
    public function send(Request $request, $json_e, $options)
    {
    	$temp = [
    		'json' 	   => json_decode($json_e, true),
    		'username' => $request->get('username'),
			'password' => $request->get('password'),
			'options'  => $options
    	];
    	$headers = [
			'Accept' => 'application/json',
			'Content-Type' => 'application/json',
		];
		$response = \Unirest\Request::post('http://receiver.api/json', $headers, json_encode($temp));
        //dd($response->raw_body);
		return $response->raw_body;
    }

    /**
     * Return list of sended files.
     * 
     * @param  object $files DB History model object 
     * @return HTML page
     */
    public function history(History $files)
    {
    	return view('history')->with('files', $files->paginate(10));
    }

}
