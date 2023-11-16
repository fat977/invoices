<?php
namespace App\Http\traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait attachement{

    public function uploadFile($file, $path)
    {
        $file->store('public/attachements/'.$path);
        return $file->hashName();
    }
 
}