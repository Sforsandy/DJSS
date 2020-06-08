<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use App\LeaderboardPoint;
use Auth;
use Session;
use Validator;
use Toastr;
use Response;
use DataTables;
use Illuminate\Support\Facades\Input;
class LeaderboardPointController extends Controller {
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
    	return view('leaderboard_points/index');
    }
    public function create()
    {
        return view('leaderboard_points/create');
    }

    public function data()
    {
        $LeaderboardPoints = LeaderboardPoint::all();
        return DataTables::of($LeaderboardPoints)
        ->addColumn('action', function ($getLeaderboardPoint) {

            $editBtn = "<a href='".route('leaderboard-point.edit', $getLeaderboardPoint->id)."' class='purple'> <i class='fa fa-pencil'></i> </a>";
            $deleteBtn = '';
            
            $deleteBtn = "<a href='javascript:;' id='deleteUser' data-id='".$getLeaderboardPoint->id."' data-name='".$getLeaderboardPoint->point_condition."' onclick='if (confirm(\"Are you sure you want to delete ?\")) deleteRow(".$getLeaderboardPoint->id.") ' class='blue'> <i class='fa fa-trash'></i> </a>";
            return $editBtn.$deleteBtn;
        })
        ->rawColumns(['point_condition','status', 'action'])

        ->make(true);
    }


    public function store(Request $request)
    {
        $rules = array(
            'point_condition' => 'required|max:100|unique:leaderboard_points',
            'title' => 'required',
            'point' => 'required'
        );


        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails()) 
            return Response::json(array('success' => 0,'message' => $validator->getMessageBag()->toArray()));
        else {

            $point_condition = $request['point_condition'];
            $point = $request['point'];
            $title = $request['title'];
            
            $LeaderboardPoint = new LeaderboardPoint();
            $LeaderboardPoint->point_condition = $point_condition;
            $LeaderboardPoint->point = $point;
            $LeaderboardPoint->title = $title;
            $LeaderboardPoint->save(); 
            
            
            return Response::json(array('success' => 1,'message'=>'Leaderboard point added successfully.','data' => $LeaderboardPoint));
        }
    }

    public function edit($id) 
    {
        $data = LeaderboardPoint::find($id);
        return view('leaderboard_points/edit', compact('data'));
    }

    public function update(Request $request)
    {
        $rules = array(
            'point_condition' => 'required|max:100|unique:leaderboard_points,point_condition,'.$request->id,
            'title' => 'required',
            'point' => 'required'
        );

        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails()) 
            return Response::json(array('success' => 0,'message' => $validator->getMessageBag()->toArray()));
        else {
            $point_condition = $request['point_condition'];
            $point = $request['point'];
            $title = $request['title'];
            $id = $request['id'];

            $LeaderboardPoint = LeaderboardPoint::find($id);
            $LeaderboardPoint->point_condition = $point_condition;
            $LeaderboardPoint->point = $point;
            $LeaderboardPoint->title = $title;
            $LeaderboardPoint->save();
            
            return Response::json(array('success' => 1,'message'=>'Leaderboard point update successfully.','data' => $LeaderboardPoint));
        }
    }

    public function destroy(Request $request)
    {
        LeaderboardPoint::find($request->id)->delete();
        return response()->json();
    }
}