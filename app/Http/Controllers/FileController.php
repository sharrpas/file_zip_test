<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Constants\Status;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class FileController extends Controller
{
    public function store(Request $request)
    {
        $validated_data = Validator::make($request->all(), [
            'title' => 'required|string',
            'file' => 'required|file|max:51200',
        ]);
        if ($validated_data->fails())
            return $this->error(Status::VALIDATION_FAILED, $validated_data->errors()->first());


        $StorageDir = Storage::path('files/');


        $zipFileName = date('Ymdhis') . rand(100, 999);
        $zipFormat = '.zip';

        $zip = new ZipArchive();
        $zip->open($StorageDir . $zipFileName . $zipFormat, ZipArchive::CREATE);
        $zip->addFile($request->file);
        $zip->close();




        File::query()->create([
            'title' => $zipFileName,
            'format' => $zipFormat,
        ]);


        return $this->success(Storage::url('files/' . $zipFileName . $zipFormat));
    }
}
