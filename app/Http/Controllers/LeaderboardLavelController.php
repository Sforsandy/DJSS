<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use App\LeaderboardLavel;
use Auth;
use Session;
use Validator;
use Toastr;
use Response;
use DataTables;
use Illuminate\Support\Facades\Input;
class LeaderboardLavelController extends Controller {
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
    	return view('leaderboard_lavels/index');
    }
    public function create()
    {
        return view('leaderboard_lavels/create');
    }

    public function data()
    {
        $LeaderboardLavels = LeaderboardLavel::all();
        return DataTables::of($LeaderboardLavels)
        ->addColumn('action', function ($getLeaderboardLavel) {

            $editBtn = "<a href='".route('leaderboard-lavel.edit', $getLeaderboardLavel->id)."' class='purple'> <i class='fa fa-pencil'></i> </a>";
            $deleteBtn = '';
            
            $deleteBtn = "<a href='javascript:;' id='deleteUser' data-id='".$getLeaderboardLavel->id."' onclick='if (confirm(\"Are you sure you want to delete ?\")) deleteRow(".$getLeaderboardLavel->id.") ' class='blue'> <i class='fa fa-trash'></i> </a>";
            return $editBtn.$deleteBtn;
        })
        ->rawColumns(['point_condition','status', 'action'])

        ->make(true);
    }


    public function store(Request $request)
    {
        $rules = array(
            'start_point' => 'required|max:10000|unique:leaderboard_lavels,start_point',
            'end_point' => 'required|max:10000|unique:leaderboard_lavels,end_point',
            'lavel' => 'required|unique:leaderboard_lavels,lavel'
        );


        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails()) 
            return Response::json(array('success' => 0,'message' => $validator->getMessageBag()->toArray()));
        else {

            $start_point = $request['start_point'];
            $end_point = $request['end_point'];
            $lavel = $request['lavel'];
            
            $LeaderboardLavel = new LeaderboardLavel();
            $LeaderboardLavel->start_point = $start_point;
            $LeaderboardLavel->end_point = $end_point;
            $LeaderboardLavel->lavel = $lavel;
            $LeaderboardLavel->save(); 
            
            
            return Response::json(array('success' => 1,'message'=>'Leaderboard lavel added successfully.','data' => $LeaderboardLavel));
        }
    }

    public function edit($id) 
    {
        $data = LeaderboardLavel::find($id);
        return view('leaderboard_lavels/edit', compact('data'));
    }

    public function update(Request $request)
    {
        $rules = array(
            'start_point' => 'required|max:10000|unique:leaderboard_lavels,start_point,'.$request->id,
            'end_point' => 'required|max:10000|unique:leaderboard_lavels,end_point,'.$request->id,
            'lavel' => 'required|unique:leaderboard_lavels,lavel,'.$request->id
        );

        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails()) 
            return Response::json(array('success' => 0,'message' => $validator->getMessageBag()->toArray()));
        else {
            $start_point = $request['start_point'];
            $end_point = $request['end_point'];
            $lavel = $request['lavel'];
            $id = $request['id'];

            $LeaderboardLavel = LeaderboardLavel::find($id);
            $LeaderboardLavel->start_point = $start_point;
            $LeaderboardLavel->end_point = $end_point;
            $LeaderboardLavel->lavel = $lavel;
            $LeaderboardLavel->save();
            
            return Response::json(array('success' => 1,'message'=>'Leaderboard lavel update successfully.','data' => $LeaderboardLavel));
        }
    }

    public function destroy(Request $request)
    {
        LeaderboardLavel::find($request->id)->delete();
        return response()->json();
    }
}