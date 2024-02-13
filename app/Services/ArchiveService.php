<?php

namespace App\Services;

use App\Models\File;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ArchiveService {

    public function store($mainFile): string
    {
        $storageDir = Storage::path('files/');
        $fileName = pathinfo($mainFile->title,PATHINFO_FILENAME);
        $file = $storageDir.$mainFile->title;

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




        return $fileName;
    }

}
