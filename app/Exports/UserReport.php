<?php

namespace App\Exports;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class UserReport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents, WithTitle
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
                $cellRange = 'A1:O1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(11)->setBold(true);
            },
        ];
    }
    public function title(): string
    {
    	$startdate  = $this->startdate;
    	$enddate  = $this->enddate;
        return 'Users '.$startdate.' To '.$enddate;
    }
    public function collection()
    {
    	$startdate  = $this->startdate;
    	$enddate  = $this->enddate;
    	$EndDate =  Carbon::createFromFormat('Y-m-d', $enddate)->addDays(1)->toDateTimeString();
    	$Users = User::with('roles')->whereBetween('reg_date',[$startdate,$EndDate])->get();
		$data = [];
    	$i = 0;
    	foreach ($Users as $key => $value) {
    		$data['no'] = ($i + 1);
    		$data['firstname'] = $value->firstname;
    		$data['lastname'] = $value->lastname;
    		$data['email'] = $value->email;
    		$data['facebook_id'] = $value->facebook_id;
    		$data['mobile_no'] = $value->mobile_no;
    		$data['character_name'] = $value->character_name;
    		$data['gender'] = $value->gender;
    		$data['paymentupi'] = $value->paymentupi;
    		$data['area'] = $value->area;
    		$data['city'] = $value->city;
    		$data['state'] = $value->state;
    		$data['country'] = $value->country;
    		$data['roles'] = $value->roles[0]->name;
    		$data['reg_date'] = Carbon::parse($value->reg_date)->format('d-m-Y h:i:s');
    	}
    	return collect(array($data));
    }
    public function headings(): array
    {
        return [
            'No.',
            'FirstName',
            'LastName',
            'Email',
            'Facebook id',
            'Mobile no',
            'Character name',
            'Gender',
            'PaymentUPI',
            'Area',
            'City',
            'State',
            'Country',
            'Roles',
            'Reg. date'
        ];
    }
}
