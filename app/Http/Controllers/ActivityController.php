<?php

namespace App\Http\Controllers;

use App\Activity;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    public function lastActivityGet()
    {

        $now = Carbon::now();

        $latest = Activity::where('user_id',Auth::id())->orderBy('created_at','desc')->first();
        if ($latest && is_null($latest->end)){
//            $latest->diffS = $now->diffInSeconds($latest->created_at);
            $latest->diff = $now->diffForHumans($latest->created_at);
            $latest->diffS = $now->diffInSeconds($latest->created_at);
            return $latest;
        }else{
            return true;
        }

    }
    public function activitiesGet()
    {

        return Activity::where('user_id',Auth::id())->where('end','!=',null)->orderBy('created_at','desc')->get();

    }

    public function addActivityPost(Request $request)
    {
        $request->validate([
            'title' => 'required',
        ]);


        Activity::create($request->all());

        return Activity::all();
    }
    public function stopActivityPost(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);
        $now = Carbon::now();


        $activity = Activity::find($request->id);
        $activity->duration = $now->diffInSeconds($activity->created_at);
        $activity->end=$now;
        $activity->save();


        return true;
    }
}
