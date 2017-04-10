<?php 

namespace App\Helpers;

class Encryption
{
    /**
     * List of encryption methods.
     * 
     * @return array
     */
    public static $ssl_methods = 
    [
        0  => 'AES-128-CBC',
        1  => 'AES-192-CBC',
        2  => 'AES-256-CBC',
        3  => 'BF-CBC',
        4  => 'CAST5-CBC',
        5  => 'DES-CBC',
        6  => 'DES-EDE-CBC',
        7  => 'DES-EDE3-CBC',
        8  => 'DESX-CBC',
        9  => 'IDEA-CBC',
        10 => 'RC2-40-CBC',
        11 => 'RC2-64-CBC',
        12 => 'RC2-CBC',
        13 => 'RC4',
        14 => 'RC4-40',
    ];

    protected static $hex_key = '1e4f5b283a2cd91a8ff95064f776d633';

    /**
     * Encoding string with current ssl method and return encoded value
     * 
     * @param  string $method Encryption method from $ssl_methods array 
     * @param  string $key    Secret key for encryption 
     * @param  string $iv     Initialization vector for encryption
     * @return string         Encoded string
     */
    public static function encrypt($string, $method, $iv)
    {
        return openssl_encrypt($string, $method, hex2bin(self::$hex_key), 0, $iv);
    }

    /**
     * Decoding string that was encoded by ssl method and return decoded value
     * 
     * @param  string $method Encryption method from $ssl_methods array 
     * @param  string $key    Secret key for decryption 
     * @param  string $iv     Initialization vector for encryption
     * @return string         Decoded string
     */
    public static function decrypt($safe, $method, $iv)
    {
        return openssl_decrypt($safe, $string, $method, hex2bin(self::$hex_key), 0, $iv);
    }
}