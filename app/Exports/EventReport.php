<?php

namespace App\Exports;

use App\Event;
use App\EventJoin;
use DB;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;


class EventReport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents, WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $startdate;
    protected $enddate;
    public function __construct($startdate, $enddate)
    {
        $this->startdate = $startdate;
        $this->enddate = $enddate; 
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:S1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(11)->setBold(true);
            },
        ];
    }
    public function title(): string
    {
    	$startdate  = $this->startdate;
    	$enddate  = $this->enddate;
        return 'Events '.$startdate.' To '.$enddate;
    }
    public function collection()
    {
    	$startdate  = $this->startdate;
    	$enddate  = $this->enddate;
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
        $data = array();
        foreach ($EventsArr as $EventKey => $EventValue) {
          $status = 'Upcoming';
          if($EventValue['status'] == '1')
          {
            $status = 'Ongoing';
          }
          if($EventValue['status']=='2')
          {
            $status = 'Past';
          }
           $data[$EventKey]['no'] = ($EventKey + 1);
           $data[$EventKey]['id'] = $EventValue['id'];
           $data[$EventKey]['event_name'] = $EventValue['event_name'];
           $data[$EventKey]['event_description'] = $EventValue['event_description'];
           $data[$EventKey]['event_type'] = $EventValue['event_types']['event_type_name'];
           $data[$EventKey]['event_format'] = $EventValue['event_formats']['event_format_name'];
           $data[$EventKey]['game'] = $EventValue['games']['game_name'];
           $data[$EventKey]['joined'] = 0;
           $data[$EventKey]['capacity'] = $EventValue['capacity'];
           $data[$EventKey]['team_size'] = $EventValue['team_size'];
           $data[$EventKey]['fee'] = ($EventValue['fee'] > 0) ? '₹ '.$EventValue['fee'] : 'Free';
           $data[$EventKey]['schedule_date'] = Carbon::parse($EventValue['schedule_date'])->format('d-m-Y');
           $data[$EventKey]['schedule_time'] = Carbon::parse($EventValue['schedule_time'])->format('h:i A');
           $data[$EventKey]['location'] = $EventValue['location'];
           $data[$EventKey]['created_by'] = $EventValue['creaters']['firstname'].' '.$EventValue['creaters']['lastname'];
           $data[$EventKey]['access_details'] = $EventValue['access_details'];
           $data[$EventKey]['stream_url'] = $EventValue['stream_url'];
           $data[$EventKey]['status'] = $status;
           $data[$EventKey]['total_prize'] = $EventValue['total_prize'];
           foreach ($EventJoinArr as $key => $value) {
               if($value->event_id == $EventValue['id'])
               {
                $data[$EventKey]['joined'] = $value->total;
               }
           }
        }
        
    	return collect(array($data));
    }
    public function headings(): array
    {
        return [
            'No.'
            ,'Id'
			,'Event Name'
			,'Event Description'
			,'Event Type'
			,'Event Format'
			,'Game'
			,'Joined'
			,'Capacity'
			,'Team Size'
			,'Fee'
			,'Schedule Date'
			,'Schedule Time'
			,'Location'
			,'Created By'
			,'Access Details'
			,'Stream Url'
			,'Status'
			,'Total Prize'
        ];
    }
}
