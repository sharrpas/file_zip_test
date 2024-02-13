<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class FileResource extends JsonResource
{

    private $format;


    public function __construct($resource, $format)
    {
        parent::__construct($resource);
        $this->resource = $resource;

        $this->format = $format;
    }

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'main_file' => Storage::url('files/' . $this->title),
            'archived_file' => Storage::url('files/' . pathinfo($this->title, PATHINFO_FILENAME) . '.' . $this->format),
        ];
    }


}
