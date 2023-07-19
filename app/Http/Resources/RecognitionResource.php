<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecognitionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'id_service_video' => $this->id_service_video,
            'user' => $this->user,
            'progetto' => $this->progetto,
            'time' => round($this->time),
            'age' => number_format($this->age,6,',','.'),
            'gender' => $this->gender,
            'angry' => number_format($this->angry,6,',','.'),
            'disgusted' => number_format($this->disgusted,6,',','.'),
            'fearful' => number_format($this->fearful,6,',','.'),
            'happy' => number_format($this->happy,6,',','.'),
            'neutral' => number_format($this->neutral,6,',','.'),
            'sad' => number_format($this->sad,6,',','.'),
            'surprised' => number_format($this->surprised,6,',','.'),


        ];
    }
}
