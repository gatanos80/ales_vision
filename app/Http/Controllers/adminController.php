<?php

namespace App\Http\Controllers;
use App\Providers\RouteServiceProvider;
use Ramsey\Uuid\Uuid;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Response as HttpResponse;

use Illuminate\Http\RedirectResponse;

use App\Models\Video;



class adminController extends Controller
{
    public function addVideo (Request $request) : Response
    {
        return Inertia::render('Admin/addVideo');
    }

    public function saveVideo (Request $request) : RedirectResponse
    {
      
        $validators= [
            'code' => 'required|unique:videos|max:255',
            'title' => 'required|max:255',
            'id_video' => 'required|max:255',
            'url_video' => 'required|max:255',
        ];
        if($request->id)  $validators['code'] = 'required|unique:videos,code,'.$request->id;
        
        $validated = $request->validate($validators);
        if(!$request->id){

            $video = Video::create($request->input());
        } else {
            $video = Video::where('id', $request->id)->first();
            $video->code = $request->code;
            $video->title = $request->title;
            $video->id_video = $request->id_video;
            $video->url_video = $request->url_video;

            $video->save();
            
        }
        return redirect(RouteServiceProvider::HOME);
        
    }

    public function editVideo (Request $request,  $id) : Response
    {
        //var_dump($video->first());
        //exit;

        return Inertia::render('Admin/addVideo', ['video' =>Video::where('id',$id)->first()]);

    }

    public function videoResults(Request $request) : Response | HttpResponse
    {
        if(!$request->id ) return abort(404);
// 
        return Inertia::render('Admin/videoResults',['video' =>Video::where('id',$request->id)->first()]);

    }
}
