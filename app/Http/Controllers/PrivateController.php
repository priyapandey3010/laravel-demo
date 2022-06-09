<?php 

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

class PrivateController extends BaseController 
{
    public function formats($file)
    {
        $path = "/private/formats/{$file}";

        if (Storage::exists($path)) 
        {
            return Storage::download($path);
        }

        abort(404);
    }
}