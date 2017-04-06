<?php 

namespace App\Helpers;

use ZipArchive;

class ZipToJson
{
	public static $all_opt =   JSON_HEX_TAG | 
							   JSON_HEX_APOS | 
							   JSON_HEX_QUOT | 
							   JSON_HEX_AMP |     			  
							   JSON_PRETTY_PRINT | 
							   JSON_UNESCAPED_SLASHES |
							   JSON_UNESCAPED_UNICODE;
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
     * Get name of zip-file and return structure in array.
     *
     */
    public static function read_zip(string $file)
    {
    	$za = new ZipArchive(); 
		$za->open($file); 
		$ds = DIRECTORY_SEPARATOR;
		$structure = [];
		$last_dir =& $structure;

		for( $i = 0; $i < $za->numFiles; $i++ ){ 
		    $stat = $za->statIndex( $i );

		    if($stat['size'] == '0'){ //if size == 0 it's a folder
		    	$ds = substr($stat['name'], -1); //getting directory separator
		    	$folders = explode($ds, $stat['name']);
			    $temp =& $structure;
			    for ($a=0; $a < count($folders)-1; $a++) { 
			    	if(!isset($temp[$folders[$a]])){
			    		$temp[$folders[$a]] = [];
			    		$last_dir =& $temp[$folders[$a]];
			    	}		
			    	$temp =& $temp[$folders[$a]];
			    }	
		    } else { //it's a file
		    	array_push($last_dir, basename( $stat['name'] ));
		    }
		}

		return $structure;
    }

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