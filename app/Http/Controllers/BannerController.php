<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use App\Banner;
use Auth;
use Session;
use Validator;
use Toastr;
use Response;
use DataTables;
use Illuminate\Support\Facades\Input;
class BannerController extends Controller {
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
    	return view('banners/index');
    }
    public function create()
    {
        return view('banners/create');
    }

    public function data()
    {
        $Banners = Banner::all();
        return DataTables::of($Banners)
        ->addColumn('banner_image', function ($getBanner) {

            $bannerImabe = "<a target='_blank' href=".url('public/uploads/banners').'/'.$getBanner->banner_image."> <img style='width: 75px;' src=".url('public/uploads/banners').'/'.$getBanner->banner_image."></a>";
            return $bannerImabe;
        })
        ->addColumn('action', function ($getBanner) {

            $editBtn = "<a href='".route('banner.edit', $getBanner->id)."' class='purple'> <i class='fa fa-pencil'></i> </a>";
            $deleteBtn = '';
            
            $deleteBtn = "<a href='javascript:;' id='deleteUser' data-id='".$getBanner->id."'  onclick='if (confirm(\"Are you sure you want to delete ?\")) deleteRow(".$getBanner->id.") ' class='blue'> <i class='fa fa-trash'></i> </a>";
            return $editBtn.$deleteBtn;
        })
        ->rawColumns(['banner_image','action'])

        ->make(true);
    }


    public function store(Request $request)
    {
        $rules = array(
            'banner_image' => 'required|image|mimes:jpeg,png,jpg|max:1024'
        );


        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails()) 
            return Response::json(array('success' => 0,'message' => $validator->getMessageBag()->toArray()));
        else {
            $bannerImageName = '';
            if($request->hasFile('banner_image')) {
                $fileGET = $request->file('banner_image');
                $bannerImageName = time().round(100,999).'.'.$fileGET->getClientOriginalExtension();
                $request->file('banner_image')->move(public_path("/uploads/banners"), $bannerImageName);
            }
            $banner_url = $request['banner_url'];
            $banner_data = $request['banner_data'];
            $banner_image = $bannerImageName;
            
            $bannerAdd = new Banner();
            $bannerAdd->banner_url = $banner_url;
            $bannerAdd->banner_image = $banner_image;
            $bannerAdd->banner_data = $banner_data;
            $bannerAdd->save(); 
            
            
            return Response::json(array('success' => 1,'message'=>'Banner added successfully.','data' => $bannerAdd));
        }
    }

    public function edit($id) 
    {
        $data = Banner::find($id);
        return view('banners/edit', compact('data'));
    }

    public function update(Request $request)
    {
        $rules = array(
            'banner_image' => 'sometimes|image|mimes:jpeg,png,jpg|max:1024'
        );

        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails()) 
            return Response::json(array('success' => 0,'message' => $validator->getMessageBag()->toArray()));
        else {
            if($request->hasFile('banner_image')) {
                $fileGET = $request->file('banner_image');
                $bannerImageName = time().round(100,999).'.'.$fileGET->getClientOriginalExtension();
                $request->file('banner_image')->move(public_path("/uploads/banners"), $bannerImageName);
            }
            $banner_url = $request['banner_url'];
            $banner_data = $request['banner_data'];
            $id = $request['id'];

            $bannerAdd = Banner::find($id);
            $bannerAdd->banner_url = $banner_url;
            $bannerAdd->banner_data = $banner_data;
            if($request->hasFile('banner_image')) {
            $bannerAdd->banner_image = $bannerImageName;
            }
            $bannerAdd->save();
            
            return Response::json(array('success' => 1,'message'=>'Banner update successfully.','data' => $bannerAdd));
        }
    }

    public function destroy(Request $request)
    {
        Banner::find($request->id)->delete();
        return response()->json();
    }
}