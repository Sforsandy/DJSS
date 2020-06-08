<?php


namespace App\Helpers;
use Request;
use App\LogActivity as LogActivityModel;


class LogActivity
{


    public static function addToLog($subject,$api_name ="",$device_type="",$post_string="")
    {
    	$log = [];
    	$log['subject'] = $subject;
    	$log['url'] = Request::fullUrl();
    	$log['method'] = Request::method();
    	$log['ip'] = Request::ip();
    	$log['agent'] = Request::header('user-agent');
    	$log['device_type'] = $device_type;
    	$log['api_name'] = $api_name;
    	$log['post_string'] = serialize(Request::all());
    	$log['user_id'] = auth()->check() ? auth()->user()->id : '';
    	LogActivityModel::create($log);
    }


    public static function logActivityLists()
    {
    	return LogActivityModel::latest()->get();
    }


}