<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

class DownloadCont extends BaseController
{
	public function downloadFile($file){
        $path = '../storage/app/test/'.$file;
        return response()->download($path, $file);
		
    }
/*$filename = 'test.pdf';
$path = storage_path($filename);

return Response::make(file_get_contents($path), 200, [
    'Content-Type' => 'application/pdf',
    'Content-Disposition' => 'inline; filename="'.$filename.'"'
]);*/
}
