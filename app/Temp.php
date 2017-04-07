<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Temp extends Model
{
    protected $table = 'temp';

    protected $fillable = ['filename', 'method', 'hex_key', 'hex_iv', 'created_at', 'updated_at'];

    /**
     * Add new row to temp table with file structure encoding info.
     * 
     * @param  string  $filename
     * @param  string  $method   Encryption method.
     * @param  string  $hex_key  Hexadecimal key for encryption.
     * @param  string  $hex_iv   Hexadecimal initialization vector for encryption.
     * @return numeric $id       Row id in temp table.
     */
    public static function new($filename, $method, $hex_key, $hex_iv)
    {
        $array = [
            'filename'=> $filename,
            'method'  => $method,
            'hex_key' => $hex_key,
            'hex_iv'  => $hex_iv,
        ];
    	$temp = Temp::create($array);
        return $temp->id;
    }
}