<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use PDF;
use Excel;
use App\Exports\UserReport;
use App\Exports\EventReport;
use App\Event;
use Auth;
use DB;
use App\EventJoin;
use App\User;
use Carbon\Carbon;
class ReportController extends Controller {
    
    public function __construct(\Maatwebsite\Excel\Exporter $excel)
    {
        $this->excel = $excel;
    }
    public function UserReportPdf($startdate,$enddate)
    {
        if($startdate == '' || $enddate ==  '')
        {
            return view('errors.408');
        }
        $EndDate =  Carbon::createFromFormat('Y-m-d', $enddate)->addDays(1)->toDateTimeString();
            $Users = User::with('roles')->whereBetween('reg_date',[$startdate,$EndDate])->get();
        $UserData = '';
        foreach ($Users as $key => $value) {
            $UserData .= '
            <tr>
                <td>'.$value['firstname'].' '.$value['lastname'].'</td>
                <td>'.$value['character_name'].'</td>
                <td>'.$value['email'].'</td>
                <td>'.$value['mobile_no'].'</td>
                <td>'.$value['roles'][0]['display_name'].'</td>
                <td>'.Carbon::parse($value['reg_date'])->format('d-m-Y').'</td>
            </tr>';
        }
        $dateField = Carbon::parse($startdate)->format('d-m-Y').' to '.Carbon::parse($enddate)->format('d-m-Y');
        $html = '<h1>User report date : '.$dateField.'</h1>';
        $html .= '
        <table cellspacing="0" cellpadding="2" border="0.2">
        <tr>
            <th style="width:25%"><b>Name</b></th>
            <th style="width:15%"><b>Character Name</b></th>
            <th style="width:30%"><b>Email</b></th>
            <th style="width:10%"><b>Mobile</b></th>
            <th style="width:10%"><b>Role</b></th>
            <th style="width:10%"><b>Reg, Date</b></th>
        </tr>
        '.$UserData.'
        </table>';


        PDF::SetFont('times', '', 9);
        PDF::SetTitle('UserReport');
        PDF::AddPage();
        PDF::writeHTML($html, true, false, true, false, '');

        PDF::Output('UserReport'.time().'-'.$dateField.'.pdf');
    }
    public function UserReport($startdate,$enddate)
    {
        if($startdate == '' || $enddate ==  '')
        {
            return view('errors.408');
            
        }
        $dateField = Carbon::parse($startdate)->format('d-m-Y').' to '.Carbon::parse($enddate)->format('d-m-Y');
        return Excel::download(new UserReport($startdate,$enddate), 'UserReport'.time().'-'.$dateField.'.xlsx');
    }
    public function EventReportPdf($startdate,$enddate)
    {
        if($startdate == '' || $enddate ==  '')
        {
            return view('errors.408');
            
        }
        $EndDate =  Carbon::createFromFormat('Y-m-d', $enddate)->addDays(1)->toDateTimeString();
        if(Auth::user()->hasRole('admin'))
        {
            $Events = Event::with('event_types','event_formats','creaters','games')->whereBetween('schedule_date',[$startdate,$EndDate])->get();
        }else{
            $Events = Event::where('created_by',Auth::user()->id)->with('event_types','event_formats','creaters','games')->whereBetween('schedule_date',[$startdate,$EndDate])->get();
        }

        $EventsArr = $Events->toArray();
        $EventIds = array_column($EventsArr, 'id');
        $EventJoin = DB::table('event_joined_users')
                 ->select('event_id', DB::raw('count(*) as total'))
                 ->whereIn('event_id',$EventIds)
                 ->groupBy('event_id')
                 ->get();
        $EventJoinArr = $EventJoin->toArray();

        foreach ($EventsArr as $EventKey => $EventValue) {
           $EventsArr[$EventKey]['joined'] = 0;
           foreach ($EventJoinArr as $key => $value) {
               if($value->event_id == $EventValue['id'])
               {
                $EventsArr[$EventKey]['joined'] = $value->total;
               }
           }
        }
        // echo "<pre>";
        // print_r($EventsArr);
        // print_r($EventJoinArr);
        // die;
        $EventData = '';
        foreach ($EventsArr as $key => $value) {
            $fee = ($value['fee'] != '') ? '₹'.$value['fee'] : 'Free';
            $schedule_datetime = Carbon::parse($value['schedule_datetime'])->format('d-m-Y h:i A');
            $EventData .= '
            <tr>
                <td style="width:21%">'.$value['event_name'].'</td>
                <td style="width:10%">'.$value['event_types']['event_type_name'].'</td>
                <td style="width:7%">'.$value['event_formats']['event_format_name'].'</td>
                <td style="width:15%">'.$value['games']['game_name'].'</td>
                <td style="width:8%">'.$value['joined'].'/'.$value['capacity'].'</td>
                <td style="width:5%">'.$fee.'</td>
                <td style="width:10%">'.$schedule_datetime.'</td>
                <td style="width:15%">'.$value['creaters']['firstname'].' '.$value['creaters']['lastname'].'</td>
                <td style="width:9%">'.$value['location'].'</td>
            </tr>';
        }
        $dateField = Carbon::parse($startdate)->format('d-m-Y').' to '.Carbon::parse($enddate)->format('d-m-Y');
        $html = '<h1>Event report date : '.$dateField.'</h1>';
        $html .= '
        <table cellspacing="0" cellpadding="2" border="0.2">
        <tr>
            <th style="width:21%"><b>Event name</b></th>
            <th style="width:10%"><b>Type</b></th>
            <th style="width:7%"><b>Format</b></th>
            <th style="width:15%"><b>Game</b></th>
            <th style="width:8%"><b>Capacity</b></th>
            <th style="width:5%"><b>Fee</b></th>
            <th style="width:10%"><b>Date</b></th>
            <th style="width:15%"><b>Created by</b></th>
            <th style="width:9%"><b>Location</b></th>
        </tr>
        '.$EventData.'
        </table>';


        PDF::SetFont('times', '', 9);
        PDF::SetTitle('EventReport');
        PDF::AddPage();
        PDF::writeHTML($html, true, false, true, false, '');

        PDF::Output('EventReport'.time().'-'.$dateField.'.pdf');
    }

    public function EventReport($startdate,$enddate)
    {
        if($startdate == '' || $enddate ==  '')
        {
            return view('errors.408');
            
        }
        $dateField = Carbon::parse($startdate)->format('d-m-Y').' to '.Carbon::parse($enddate)->format('d-m-Y');
        return Excel::download(new EventReport($startdate,$enddate), 'EventReport'.time().'-'.$dateField.'.xlsx');
    }
}