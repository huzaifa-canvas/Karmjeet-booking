<?php

use App\Models\CMSPages;
use App\Models\Coaching;
use App\Models\PagesContent;
use App\Models\SessionBooking;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Spatie\GoogleCalendar\Event;
use Illuminate\Support\Facades\Auth;

function navActive($route_name)
{
    return (in_array(request()->route()->getName(), $route_name) ? "active" : '');
}


function slugGenerator($value, $model ,$column_name)
{
    $slug = Str::slug($value);

    if ($model::where($column_name, $slug)->exists()) {
        $slug = $slug.'-'.date('dmyhis');
    }

    return $slug;
}

 function getPages()
{
    return PagesContent::where('status',1);
}

 function getFreeSessionUrl()
{
    return Coaching::where('price_per_session',0)->first();
}

 function getUserSessionCount()
{
    $user = Auth::user();
    return SessionBooking::where('user_id', $user->id)->count();
}

function createZoomMettingUrl($data)
{
    $body = [
            "agenda" => "My Meeting",
            "topic"  => "My Meeting",
            "default_password" => false,
            "duration" => $data->duration,
            "password" => "123456",
            "pre_schedule" => false,
            "contact_email" => $data->email,
            "contact_name" => $data->full_name,
            "email_notification" => true,
            // "start_time" => date('Y-m-d', strtotime($data->date))."T".$data->start_time."Z",
            "start_time" => Carbon\Carbon::createFromDate( $data->date->format('Y-m-d')." ".$data->start_time),
            "host_video" => true,
            "participant_video" => true
    ];
    // dd($body);

    $response = Http::withHeaders([
        'Content-Type' => 'application/json',
        'Authorization'=> 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOm51bGwsImlzcyI6IjJOUmlUamVMUVVxS19RQ0JDNkt6eEEiLCJleHAiOjIwODAwNjIwMDAsImlhdCI6MTY3MzY0NjA3MH0.4jR2HZq85aqkoUnFIayj1uBDc9AS8vRN-EomerqGaPw',
        // 'Authorization'=> 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOm51bGwsImlzcyI6InhVNHB5M2VjVExtR1lIazdJazNLMUEiLCJleHAiOjIwODI1Njc2MDAsImlhdCI6MTY3Mjg2MDE4OH0.ss7-32NbcJAFrvUad2qkzKmwfkBu5Cskas2zr5eerVI',
        // U2nMzv-mQumA02DA0V9X3Q
    ])->post('https://api.zoom.us/v2/users/z4HH1fCPTqOeJvwhEYP2tQ/meetings',$body);

    return json_decode($response);
}


function addToGoogleCalendar($data)
{
    try {
        $event = new Event;

        $event->name = $data->getSession->title;
        $event->startDateTime =  Carbon\Carbon::createFromDate( $data->date->format('Y-m-d')." ".$data->start_time);
        $event->endDateTime = Carbon\Carbon::createFromDate( $data->date->format('Y-m-d')." ".$data->end_time);

        return $event->save();

    } catch (\Throwable $th) {
        return true;
    }

}

function getPageContent($type, $key)
{
    try {
        return CMSPages::where('page_type', $type)->where('data_key', $key)->first()?->data_value;
    } catch (\Throwable $th) {
       return null;
    }
}


function getMediaType($url,$type)
{
    $imgExts = array("gif", "jpg", "jpeg", "png", "tiff", "tif");
    $vidExts = ["mp4", "3gp", "ogg"];
    $urlExt = pathinfo($url, PATHINFO_EXTENSION);
    return in_array($urlExt, $type == "img" ? $imgExts : $vidExts);
}
