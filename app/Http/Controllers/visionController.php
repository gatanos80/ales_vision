<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Http\Request;

use App\Models\Video;
use App\Models\Recognition;


class visionController extends Controller
{
    public function show (Request $request, string $uuid) : Response | HttpResponse
    {
        if(!$request->idproj || !$request->idusr ) return response('request malformed', 404);
        $video = Video::where('uuid', $uuid)->first();
        return Inertia::render('Vision/show', ['video' => $video, 'user_id' => $request->idusr, 'id_proj' => $request->idproj] );
    }

    public function saveDetection(Request $request){
        //var_dump($request->detection['expressions']['angry']);
        //exit;
        $reco =  Recognition::create([
            'id_video' => $request->id_video,
            'progetto' => $request->id_proj,
            'user' => $request->user_id,
            'id_service_video' => $request->id_service_video,
            'time' => $request->time,
            'age' => $request->detection['age'],
            'gender' => $request->detection['gender'],
            'angry' => $request->detection['expressions']['angry'],
            'disgusted' => $request->detection['expressions']['disgusted'],
            'fearful' => $request->detection['expressions']['fearful'],
            'happy' => $request->detection['expressions']['happy'],
            'neutral' => $request->detection['expressions']['neutral'],
            'sad' => $request->detection['expressions']['sad'],
            'surprised' => $request->detection['expressions']['surprised']

        ]);

        return $reco;
    
    }

}
