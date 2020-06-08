<?php
namespace App\Http\Controllers;
use DB;
use Carbon\Carbon;
class CronController extends Controller {
	

    public function Cron1mChangeEventStatus()
    {
        // UPCOMMING EVENT CHANGE TO ONGOING
        // EVENT ONGOING 15 MIN BEFORE START EVENT
        // $data = DB::table('events')
        // ->where('schedule_datetime', '<=', Carbon::now()->addMinutes(2))
        // ->where('status', 0)
        // // ->get();
        // ->update(['status' => 1]);
        
        
        
        // ONGOING EVENT CHANGE TO PAST 
        // EVENT PAST 2 HOURS AFTER START EVENT
        $data = DB::table('events')
        ->where('schedule_datetime', '<=', Carbon::now()->subMinutes(120))
        ->where('status', 1)
        // ->get();
        ->update(['status' => 2]);
        
        // echo Carbon::now()->toDateTimeString().'<br>';
        // echo Carbon::now()->subMinutes(120);
        // echo "<pre>";
        // print_r($data->toArray());
    }
}