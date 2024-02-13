<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Http\Resources\FileResource;
use App\Jobs\SaveFiles;
use App\Models\MainFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class FileController extends Controller
{

    public function index()
    {
        return $this->success(MainFile::all());
    }

    public function show(MainFile $file, $format)
    {
        $format = (array)$format;
        $validated_data = Validator::make($format, [
            0 => [
                'required',
                Rule::in(['7zip', 'zip', 'tar.gz']),
            ]
        ]);
        if ($validated_data->fails())
            return $this->error(Status::VALIDATION_FAILED, $validated_data->errors()->first());


        return $this->success(new FileResource($file, $format[0]));
    }

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

        return $this->success(Storage::url('files/' . $mainFile->title));
    }
}
