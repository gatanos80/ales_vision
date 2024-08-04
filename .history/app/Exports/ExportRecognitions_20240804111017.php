<?php

namespace App\Exports;

use App\Models\Recognition;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportRecognitions implements FromCollection, WithHeadings, WithMapping
{

    protected $video;
    protected $project;
    protected $user;

    
    public function __construct(int $video , string | null $user, string | null $project)
    {
        $this->video = $video;
        $this->user = $user;
        $this->project = $project;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $reco = Recognition::where('id_video', $this->video);
        if($this->project) $reco->where('progetto', $this->project);
        if($this->user) $reco->where('user', $this->user);
        return $reco->get();
    }

    public function headings(): array
    {
        return [
            'id_service_video', 
            'user', 
            'progetto', 
            'time', 
            'age', 
            'gender', 
            'angry', 
            'disgusted', 
            'fearful', 
            'happy', 
            'neutral', 
            'sad', 
            'surprised'
        ];
    }

        /**
    * @var Recognition $recognition
    */
    public function map($recognition): array
    {
        return [
            $recognition->id_service_video,
            $recognition->user,
            $recognition->progetto,
            round($recognition->time),
            $recognition->age,
            $recognition->gender,
            $recognition->angry,
            $recognition->disgusted,
            $recognition->fearful,
            $recognition->happy,
            $recognition->neutral,
            $recognition->sad,
            $recognition->surprised,
        ];
    }
}
