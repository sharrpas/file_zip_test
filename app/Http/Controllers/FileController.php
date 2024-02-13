<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Jobs\SaveFiles;
use App\Models\File;
use App\Models\MainFile;
use App\Services\ArchiveService;
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

        $fileName = date('Ymdhis') . rand(100, 999) . '.' . $request->file('file')->extension();

        Storage::putFileAs('files/', $request->file, $fileName);

        $mainFile = MainFile::query()->create([
            'name' => $request->name,
            'title' => $fileName,
        ]);


        SaveFiles::dispatch($mainFile);

        //      (new ArchiveService())->store($request);

        return $this->success(Storage::url('files/'));
    }
}
