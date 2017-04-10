<?php 

namespace App\Helpers;

use App\Temp;
use ZipArchive;

class ZipToJson
{
	/**
     * PHP flag of JSON transformation for function json_encode().
     * 
     * @return numeric
     */
	public static $all_opt =   JSON_HEX_TAG | 
							   JSON_HEX_APOS | 
							   JSON_HEX_QUOT | 
							   JSON_HEX_AMP |     			  
							   JSON_PRETTY_PRINT | 
							   JSON_UNESCAPED_SLASHES |
							   JSON_UNESCAPED_UNICODE;
	/**
     * Associative array with PHP flags of JSON transformation for form selector.
     * 
     * @return array
     */
	public static $opt_arr = 
	[
		'hex_tag'   => JSON_HEX_TAG,
		'hex_amp'   => JSON_HEX_AMP,
		'hex_apos'  => JSON_HEX_APOS,
		'hex_quot'  => JSON_HEX_QUOT,
		'whitespace'=> JSON_PRETTY_PRINT,
		'slashes'   => JSON_UNESCAPED_SLASHES,
		'unicode'   => JSON_UNESCAPED_UNICODE,
	];

    /**
     * Get name of zip-file and return both encrypted and simple structure array.
     * 
     * @param  string $file       Pathname of zip-file 
     * @param  string $filename   Original zip-file name 
     * @param  string $ssl_method Encryption method
     * @return array              Array with encoded structure and simple structure in JSON format 
     */
    public static function read_zip(string $file, $filename, $ssl_method)
    {
    	$za = new ZipArchive(); 
		$za->open($file); 
		$ds = DIRECTORY_SEPARATOR;
		$structure = [];
		$last_dir =& $structure;

		//for encryption data
		$structure_e = [];
		$last_dir_e =& $structure_e;
	    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($ssl_method));

		for( $i = 0; $i < $za->numFiles; $i++ ){ 
		    $stat = $za->statIndex( $i );

		    if($stat['size'] == '0'){ //if size==  0 it's a folder
		    	$ds = substr($stat['name'], -1); //getting directory separator
		    	$folders = explode($ds, $stat['name']);
			    $temp =& $structure;
			    $temp_e =& $structure_e;
			    for ($a=0; $a < count($folders)-1; $a++) { 
			    	if(!isset($temp[$folders[$a]])){
			    		$temp[$folders[$a]] = [];
			    		$last_dir =& $temp[$folders[$a]];
			    	}

			    	$folder_e = Encryption::encrypt($folders[$a], $ssl_method, $iv);
			    	if(!isset($temp_e[$folder_e])){
			    		$temp_e[$folder_e] = [];
			    		$last_dir_e =& $temp_e[$folder_e];
			    	}

			    	$temp =& $temp[$folders[$a]];
			    	$temp_e =& $temp_e[$folder_e];
			    }	
		    } else { //it's a file
		    	array_push($last_dir, basename( $stat['name'] ));

		    	$basename_e = Encryption::encrypt(basename( $stat['name'] ), $ssl_method, $iv);
		    	array_push($last_dir_e, $basename_e);
		    }
		}
		unset($temp, $temp_e, $last_dir, $last_dir_e, $folders);

		//saving encryption info to temp table
		//$temp_id = Temp::new($filename, $ssl_method, bin2hex($key), bin2hex($iv));		
		return [
			'structure' => $structure, 
			'encrypted' => [
				'array'  => $structure_e,
				'method_id'=> array_search($ssl_method, Encryption::$ssl_methods),
				'hex_iv'   => bin2hex($iv),
				'filename' => $filename
			]
		];
    }


    /**
     * Setting JSON transform options.
     * 
     * @param  object  $request Request object 
     * @param  array   $checked Selected options from form 
     * @return numeric $options PHP flag for json_encode() transformation
     */
    public static function set_options($request, array $checked)
    {
    	$options = [];
    	foreach ($checked as $key => $value) {
    		if(isset(self::$opt_arr[$key])){
    			$options |= self::$opt_arr[$key];
    		}
    	}
    	if(isset($checked['all']) || $options == self::$all_opt){
    		$request->merge(['options' => ['all' => 'all']]);
    		return self::$all_opt;
    	}
    	return $options;
    }
}