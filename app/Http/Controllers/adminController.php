<?php

namespace App\Http\Controllers;
use App\Providers\RouteServiceProvider;
use Ramsey\Uuid\Uuid;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Response as HttpResponse;

use Illuminate\Http\RedirectResponse;

use App\Models\Video;

use App\Exports\ExportRecognitions;


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
            'return_link' => 'required|max:255',
            'no_detection_label' => 'required|max:255',
            'start_label' => 'required|max:255',
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
            $video->return_link = $request->return_link;
            $video->no_detection_label = $request->no_detection_label;
            $video->start_label = $request->start_label;

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

     /**
     * Update the specified user.
     *
     * @param  Request  $request
     * @param  string  $id
     * @return Response
     */

    public function exportResultsAll(Request $request, $id) 
    {
       /* var_dump($id);
        var_dump($request->id);
        
        var_dump($request->all());
        var_dump($request->user);
        var_dump($request->project);
        exit;*/
        if(!$request->id ) return abort(404);
        $user = $request->has('user') ? $request->user : "";
        $project = $request->has('project') ? $request->project : "";
        $video = Video::where('id', $request->id)->first();
        if(!$video) return abort(404);
        $xls = new ExportRecognitions($request->id, $user, $project);
        return Excel::download($xls, $video->title.'.xlsx');
    }   
}
