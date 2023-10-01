<?php

/**
 * PHP JWT for Codeigniter
 *
 * @package        	CodeIgniter
 * @subpackage    	Libraries
 * @category    	Libraries
 * @author	        Rizky Awlia Fajrin (Evan Sumangkut)
 * @email           rizkyawliafajrin@gmail.com
 *
 * @version		1.0
 */


defined('BASEPATH') OR exit('No direct script access allowed');

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class MinioConfiguration {

    public $key;

    public function __construct()
    {

        $this->key = '!@#$3v4N%^&*';
    }

    public function encode($token)
    {
        try{
            return JWT::encode($token, $this->key, 'HS256');
         }catch(Exception $e){
            return null;
        }
    }

    public function decode($token)
    {
        try{
            return JWT::decode($token, new Key($key, 'HS256'));
        }catch(Exception $e){
            return null;
        }
    }

    public function switchMinio()
    {
        // ubah status menjadi true untuk mengaktifkan minio
        return true;
    }

    public function bucket()
    {
        // ubah status menjadi true untuk mengaktifkan minio
        $bucket = 'elhkpn';
        return $bucket;
    }

    public function storageDiskMinio()
    {
        // Storage Disk penyimpanan
        $storage = 'D';
        return $storage;
    }

    public function childPerFolder()
    {
        // Storage Disk penyimpanan
        $child = 1000;
        return $child;
    }
}
