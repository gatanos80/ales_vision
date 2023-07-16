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
            'age' => $this->age,
            'gender' => $this->gender,
            'angry' => $this->angry,
            'disgusted' => $this->disgusted,
            'fearful' => $this->fearful,
            'happy' => $this->happy,
            'neutral' => $this->neutral,
            'sad' => $this->sad,
            'surprised' => $this->surprised


        ];
    }
}
