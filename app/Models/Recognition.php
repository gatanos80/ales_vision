<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recognition extends Model
{
    use HasFactory;

    protected $fillable = ['id_video', 'progetto', 'user', 'id_service_video', 'time', 'age', 'gender', 'angry', 'disgusted', 'fearful', 'happy', 'neutral', 'sad', 'surprised' ];

}
