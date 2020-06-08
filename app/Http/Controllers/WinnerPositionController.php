<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use App\WinnerPosition;
use Auth;
use Session;
use Validator;
use Toastr;
use Response;
use DataTables;
use Illuminate\Support\Facades\Input;
class WinnerPositionController extends Controller {
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
    	return view('winner_position/index');
    }
    public function create()
    {
        return view('winner_position/create');
    }

    public function data()
    {
        $WinnerPositions = WinnerPosition::all();
        return DataTables::of($WinnerPositions)

        ->addColumn('action', function ($getWinnerPosition) {

            $editBtn = "<a href='".route('winner-position.edit', $getWinnerPosition->id)."' class='purple'> <i class='fa fa-pencil'></i> </a>";
            $deleteBtn = '';
            
            $deleteBtn = "<a href='javascript:;' id='deleteUser' onclick='if (confirm(\"Are you sure you want to delete ?\")) deleteRow(".$getWinnerPosition->id.") ' class='blue'> <i class='fa fa-trash'></i> </a>";
            return $editBtn.$deleteBtn;
        })
        ->rawColumns(['position', 'action'])

        ->make(true);
    }


    public function store(Request $request)
    {
        $rules = array(
            'position' => 'required|max:100|unique:winner_positions'
        );


        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails()) 
            return Response::json(array('success' => 0,'message' => $validator->getMessageBag()->toArray()));
        else {

            $position = $request['position'];
            
            $winnerposition = new WinnerPosition();
            $winnerposition->position = $position;
            $winnerposition->save(); 
            
            
            return Response::json(array('success' => 1,'message'=>'Winner position added successfully.','data' => $winnerposition));
        }
    }

    public function edit($id) 
    {
        $data = WinnerPosition::find($id);
        return view('winner_position/edit', compact('data'));
    }

    public function update(Request $request)
    {
        $rules = array(
            'position' => 'required|max:100|unique:winner_positions,position,'.$request->id
        );


        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails()) 
            return Response::json(array('success' => 0,'message' => $validator->getMessageBag()->toArray()));
        else {

            $position = $request['position'];
            $id = $request['id'];

            $winnerposition = WinnerPosition::find($id);
            $winnerposition->position = $position;
            $winnerposition->save();
            
            return Response::json(array('success' => 1,'message'=>'Winner position update successfully.','data' => $winnerposition));
        }
    }

    public function destroy(Request $request)
    {
        WinnerPosition::find($request->id)->delete();
        return response()->json();
    }
}