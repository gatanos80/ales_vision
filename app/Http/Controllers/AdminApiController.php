<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Response;

use App\Models\Video;
use App\Models\Recognition;

use App\Http\Resources\RecognitionResource;

use App\Http\Resources\VideoListResource;

class AdminApiController extends Controller
{
    public function getVideoList(Request $request)
    {
        
        return VideoListResource::collection(Video::all());
    }

    public function getVideoResults(Request $request){

        if(!$request->id) return response()->json(['esito' => 'not found'], 404);
        $page= $request->page ? $request->page : 1;
        $perPage= $request->perPage ? $request->perPage : 15;
        $reco = recognition::where('id_video', $request->id);
        if($request->project) $reco->where('progetto', $request->project);
        if($request->user) $reco->where('user', $request->user);

        return RecognitionResource::collection($reco->paginate($perPage, ['*'], 'page', $page));
    }

    public function getusersvideo(Request $request){
        if(!$request->id) return response()->json(['esito' => 'not found'], 404);
        return recognition::where('id_video', $request->id)->select('user')->distinct('user')->get();
    }
    
    public function getprojectsvideo(Request $request){
        if(!$request->id) return response()->json(['esito' => 'not found'], 404);
        return recognition::where('id_video', $request->id)->select('progetto')->distinct('progetto')->get();
    }
    
    
}
