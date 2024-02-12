<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Models\File;
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

        app()->make(ArchiveService::class)->store($request); //binding
//      (new ArchiveService())->store($request);

        return $this->success(Storage::url('files/'));
    }
}
