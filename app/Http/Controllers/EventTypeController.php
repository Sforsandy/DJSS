<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use App\EventType;
use Auth;
use Session;
use Validator;
use Toastr;
use Response;
use DataTables;
use Illuminate\Support\Facades\Input;
class EventTypeController extends Controller {
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
    	return view('event_type/index');
    }
    public function create()
    {
        return view('event_type/create');
    }

    public function data()
    {
        $EventTypes = EventType::all();
        return DataTables::of($EventTypes)

        ->addColumn('action', function ($getEventType) {

            $editBtn = "<a href='".route('event-type.edit', $getEventType->id)."' class='purple'> <i class='fa fa-pencil'></i> </a>";
            $deleteBtn = '';
            
            $deleteBtn = "<a href='javascript:;' id='deleteUser' data-id='".$getEventType->id."' data-name='".$getEventType->event_type_name."' onclick='if (confirm(\"Are you sure you want to delete ?\")) deleteRow(".$getEventType->id.") ' class='blue'> <i class='fa fa-trash'></i> </a>";
            return $editBtn.$deleteBtn;
        })
        ->rawColumns(['event_type_name', 'action'])

        ->make(true);
    }


    public function store(Request $request)
    {
        $rules = array(
            'event_type_name' => 'required|max:100|unique:event_types'
        );


        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails()) 
            return Response::json(array('success' => 0,'message' => $validator->getMessageBag()->toArray()));
        else {

            $event_type_name = $request['event_type_name'];
            
            $eventtype = new EventType();
            $eventtype->event_type_name = $event_type_name;
            $eventtype->save(); 
            
            
            return Response::json(array('success' => 1,'message'=>'Event type added successfully.','data' => $eventtype));
        }
    }

    public function edit($id) 
    {
        $data = EventType::find($id);
        return view('event_type/edit', compact('data'));
    }

    public function update(Request $request)
    {
        $rules = array(
            'event_type_name' => 'required|max:100|unique:event_types,event_type_name,'.$request->id
        );


        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails()) 
            return Response::json(array('success' => 0,'message' => $validator->getMessageBag()->toArray()));
        else {

            $event_type_name = $request['event_type_name'];
            $id = $request['id'];

            $eventtype = EventType::find($id);
            $eventtype->event_type_name = $event_type_name;
            $eventtype->save();
            
            return Response::json(array('success' => 1,'message'=>'Event type update successfully.','data' => $eventtype));
        }
    }

    public function destroy(Request $request)
    {
        EventType::find($request->id)->delete();
        return response()->json();
    }
}