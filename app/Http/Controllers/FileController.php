<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use ZipArchive;

class FileController extends Controller
{
    public function store(Request $request)
    {
        $validated_data = Validator::make($request->all(), [
            'name' => 'required|string',
            'file' => 'required|file|max:51200',
        ]);
        if ($validated_data->fails())
            return $this->error(Status::VALIDATION_FAILED, $validated_data->errors()->first());


        $storageDir = Storage::path('files/');
        $fileName = date('Ymdhis') . rand(100, 999);
        $file = $request->file;


        //* .zip file save
        $zipFormat = '.zip';
        $zip = new ZipArchive();
        $zip->open($storageDir . $fileName . $zipFormat, ZipArchive::CREATE);
        $zip->addFile($file);
        $zip->close();


        //* tar.gz file save
        $gzFormat = '.tar.gz';
        $gz = gzopen($storageDir . $fileName . $gzFormat, 'w9'); // w == write, 9 == highest compression
        gzwrite($gz, file_get_contents($file));
        gzclose($gz);


        //* 7zip save
        $svZipFormat = '.7zip';
        shell_exec('7z a ' . $storageDir . $fileName . $svZipFormat . ' ' . $file);


        File::query()->create([
            'name' => $request->name,
            'title' => $fileName,
            'format' => $zipFormat,
        ]);
        File::query()->create([
            'name' => $request->name,
            'title' => $fileName,
            'format' => $gzFormat,
        ]);
        File::query()->create([
            'name' => $request->name,
            'title' => $fileName,
            'format' => $svZipFormat,
        ]);


        return $this->success(Storage::url('files/' . $fileName . $zipFormat));
    }
}
