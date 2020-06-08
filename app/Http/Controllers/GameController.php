<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use App\Game;
use Auth;
use Session;
use Validator;
use Toastr;
use Response;
use DataTables;
use Illuminate\Support\Facades\Input;
class GameController extends Controller {
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
    	return view('games/index');
    }
    public function create()
    {
        return view('games/create');
    }

    public function data()
    {
        $Games = Game::where('status','1')->get();
        return DataTables::of($Games)
         ->addColumn('status', function ($getGame) {
            $btn = '';
            if($getGame->status == 1)
            {
                $btn = '<span class="label label-success">Active</span>';
            }else
            {
                $btn = '<span class="label label-warning">Inactive</span>';
            }
            
            return $btn;
        })
        ->addColumn('action', function ($getGame) {

            $editBtn = "<a href='".route('game.edit', $getGame->id)."' class='purple'> <i class='fa fa-pencil'></i> </a>";
            $deleteBtn = '';
            
            $deleteBtn = "<a href='javascript:;' id='deleteUser' data-id='".$getGame->id."' data-name='".$getGame->game_name."' onclick='if (confirm(\"Are you sure you want to delete ?\")) deleteRow(".$getGame->id.") ' class='blue'> <i class='fa fa-trash'></i> </a>";
            return $editBtn.$deleteBtn;
        })
        ->rawColumns(['game_name','status', 'action'])

        ->make(true);
    }


    public function store(Request $request)
    {
        $rules = array(
            'game_name' => 'required|max:100|unique:games',
            'game_image' => 'required|image|mimes:jpeg,png,jpg|max:1024',
            'game_banner' => 'sometimes|image|mimes:jpeg,png,jpg|max:1024' //|dimensions:width=1024,height=500|
        );


        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails()) 
            return Response::json(array('success' => 0,'message' => $validator->getMessageBag()->toArray()));
        else {
            $gameImageName = '';
            if($request->hasFile('game_image')) {
                $fileGET = $request->file('game_image');
                $gameImageName = time().rand(100,999).'.'.$fileGET->getClientOriginalExtension();
                $request->file('game_image')->move(public_path("/uploads/games"), $gameImageName);
            }
            $gameBannerName = '';
            if($request->hasFile('game_banner')) {
                $fileGET = $request->file('game_banner');
                $gameBannerName = time().rand(1000,9999).'.'.$fileGET->getClientOriginalExtension();
                $request->file('game_banner')->move(public_path("/uploads/games"), $gameBannerName);
            }
            $game_name = $request['game_name'];
            $status = $request['status'];
            $game_image = $gameImageName;
            $game_banner = $gameBannerName;
            
            $eventtype = new Game();
            $eventtype->game_name = $game_name;
            $eventtype->game_image = $game_image;
            $eventtype->game_banner = $game_banner;
            $eventtype->status = 1;
            $eventtype->save(); 
            
            
            return Response::json(array('success' => 1,'message'=>'Game added successfully.','data' => $eventtype));
        }
    }

    public function edit($id) 
    {
        $data = Game::find($id);
        return view('games/edit', compact('data'));
    }

    public function update(Request $request)
    {
        $rules = array(
            'game_name' => 'required|max:100|unique:games,game_name,'.$request->id
        );
        if($request->hasFile('game_image')) {
            $rules['game_image'] = 'required|image|mimes:jpeg,png,jpg|max:1024';
        }
        if($request->hasFile('game_banner')) {
            $rules['game_banner'] = 'sometimes|image|mimes:jpeg,png,jpg|max:1024';
        }

        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails()) 
            return Response::json(array('success' => 0,'message' => $validator->getMessageBag()->toArray()));
        else {
            if($request->hasFile('game_image')) {
                $fileGET = $request->file('game_image');
                $gameImageName = time().rand(100,999).'.'.$fileGET->getClientOriginalExtension();
                $request->file('game_image')->move(public_path("/uploads/games"), $gameImageName);
            }
            if($request->hasFile('game_banner')) {
                $fileGETBanner = $request->file('game_banner');
                $gameBannerName = time().rand(1000,9999).'.'.$fileGETBanner->getClientOriginalExtension();
                $request->file('game_banner')->move(public_path("/uploads/games"), $gameBannerName);
            }
            $game_name = $request['game_name'];
            $status = $request['status'];
            $id = $request['id'];

            $eventtype = Game::find($id);
            $eventtype->game_name = $game_name;
            $eventtype->status = ($status == 'on') ? 1 : 0;
            if($request->hasFile('game_image')) {
            $eventtype->game_image = $gameImageName;
            }
            if($request->hasFile('game_banner')) {
            $eventtype->game_banner = $gameBannerName;
            }
            $eventtype->save();
            
            return Response::json(array('success' => 1,'message'=>'Game update successfully.','data' => $eventtype));
        }
    }

    public function destroy(Request $request)
    {
        Game::find($request->id)->delete();
        return response()->json();
    }
}