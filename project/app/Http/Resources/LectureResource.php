<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\UserResource;

class LectureResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'course' => $this->course,
            'type' => $this->type,
            'faculty' => $this->faculty,
            'university' => $this->university,
            'info' => $this->info,
            'user' => new UserResource($this->user),
            'url' => env('APP_URL'). Storage::url($this->file->filename)
        ];
    }
}
