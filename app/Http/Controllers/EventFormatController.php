<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use App\EventFormat;
use Auth;
use Session;
use Validator;
use Toastr;
use Response;
use DataTables;
use Illuminate\Support\Facades\Input;
class EventFormatController extends Controller {
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
    	return view('event_format/index');
    }
    public function create()
    {
        return view('event_format/create');
    }

    public function data()
    {
        $EventFormats = EventFormat::all();
        return DataTables::of($EventFormats)

        ->addColumn('action', function ($getEventFormat) {

            $editBtn = "<a href='".route('event-format.edit', $getEventFormat->id)."' class='purple'> <i class='fa fa-pencil'></i> </a>";
            $deleteBtn = '';
            
            $deleteBtn = "<a href='javascript:;' id='deleteUser' data-id='".$getEventFormat->id."' data-name='".$getEventFormat->event_format_name."' onclick='if (confirm(\"Are you sure you want to delete ?\")) deleteRow(".$getEventFormat->id.") ' class='blue'> <i class='fa fa-trash'></i> </a>";
            return $editBtn.$deleteBtn;
        })
        ->rawColumns(['event_format_name', 'action'])

        ->make(true);
    }


    public function store(Request $request)
    {
        $rules = array(
            'event_format_name' => 'required|max:100|unique:event_formats'
        );


        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails()) 
            return Response::json(array('success' => 0,'message' => $validator->getMessageBag()->toArray()));
        else {

            $event_format_name = $request['event_format_name'];
            
            $eventformat = new EventFormat();
            $eventformat->event_format_name = $event_format_name;
            $eventformat->save(); 
            
            
            return Response::json(array('success' => 1,'message'=>'Event format added successfully.','data' => $eventformat));
        }
    }

    public function edit($id) 
    {
        $data = EventFormat::find($id);
        return view('event_format/edit', compact('data'));
    }

    public function update(Request $request)
    {
        $rules = array(
            'event_format_name' => 'required|max:100|unique:event_formats,event_format_name,'.$request->id
        );


        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails()) 
            return Response::json(array('success' => 0,'message' => $validator->getMessageBag()->toArray()));
        else {

            $event_format_name = $request['event_format_name'];
            $id = $request['id'];

            $eventformat = EventFormat::find($id);
            $eventformat->event_format_name = $event_format_name;
            $eventformat->save();
            
            return Response::json(array('success' => 1,'message'=>'Event format update successfully.','data' => $eventformat));
        }
    }

    public function destroy(Request $request)
    {
        EventFormat::find($request->id)->delete();
        return response()->json();
    }
}