<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use App\PaymentTransaction;
use App\City;
use App\State;
use Auth;
use Hash;
use DB;
use Mail;
use Session;
use Validator;
use Toastr;
use Response;
use DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
class UserController extends Controller {
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
    	return view('users/index');
    }
    public function create()
    {
        return view('users/create');
    }

    public function data(Request $request)
    {
        $StartDate = $request['StartDate'];
        $EndDate = $request['EndDate'];
        
        if($StartDate != '' && $EndDate !=  '')
        {
            $EndDate =  Carbon::createFromFormat('Y-m-d', $EndDate)->addDays(1)->toDateTimeString();
            $Users = User::with('roles')->whereBetween('reg_date',[$StartDate,$EndDate])->get();
        }else
        {
            $Users = User::with('roles');
        }
        return DataTables::of($Users)
        ->addColumn('role', function ($getUser) {
           return $getUser->roles[0]['display_name'];
        })
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
        ->addColumn('action', function ($getUser) {

            $editBtn = "<a href='".route('user.edit', $getUser->id)."' class='purple'> <i class='fa fa-eye'></i> </a>";
            $changePassBtn = "<a href='javascript:;' id='ChagePassword' data-id='".$getUser->id."' class='purple'> <i class='fa fa-key'></i> </a>";
            $TransactionBtn = "<a href='javascript:;' id='UserTransactions' data-id='".$getUser->id."' class='purple'> <i class='fa fa-history'></i> </a>";
            $RoleBtn = "<a href='javascript:;' id='ChageRole' data-id='".$getUser->id."' class='purple'> <i class='fa fa-user'></i> </a>";
            
            $deleteBtn = "<a href='javascript:;' id='deleteUser' onclick='if (confirm(\"Are you sure you want to delete ?\")) deleteRow(".$getUser->id.") ' class='blue'> <i class='fa fa-trash'></i> </a>";
            return $editBtn.$changePassBtn.$TransactionBtn.$RoleBtn.$deleteBtn;
        })
        ->rawColumns(['status', 'action'])

        ->make(true);
    }


    public function store(Request $request)
    {
        $rules = array (
            'firstname' => 'required',
            'email' => 'required|string|email|max:255|unique:users',
            'mobile_no' => 'required|numeric|digits:10|unique:users',
            'gender' => 'required',
            'role' => 'required',
        );


        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails()) 
            return Response::json(array('success' => 0,'message' => $validator->getMessageBag()->toArray()));
        else {

            $firstname = $request['firstname'];
            $lastname = $request['lastname'];
            $email = $request['email'];
            $password = '123456';
            $mobile_no = $request['mobile_no'];
            $character_name = $request['character_name'];
            $gender = $request['gender'];
            $paymentupi = $request['paymentupi'];
            $status = $request['status'];
            $role = $request['role'];

            // ADD IN USERS TABLE
            $user = new User();
            $user->firstname = $firstname;
            $user->lastname = $lastname;
            $user->email = $email;
            $user->password = Hash::make($password);
            $user->mobile_no = $mobile_no;
            $user->gender = $gender;
            $user->paymentupi = $paymentupi;
            $user->character_name = $character_name;
            $user->status = ($status == 'on') ? 1 : 0;
            $user->reg_date = date('Y-m-d H:i:s');
            $user->save();

            $user->attachRole($role);


            $data = array(
                'receiver_name' => $firstname.' '.$lastname,
                'message_content' => 'Welcome to GamerzByte India',
                'email' => $email,
                'sub' => "Welcome to GamerzByte India"
            );
            Mail::send('emails.welcome', $data, function($m) use ($data) {
                $m->from('support@gamersbyte.in', 'GamerzByte ');
                $m->to($data['email']);
                $m->subject($data['sub']);
            });
            
            
            return Response::json(array('success' => 1,'message'=>'User added successfully.','data' => $user));
        }
    }

    public function edit($id) 
    {
        $data = User::find($id);
        return view('users/edit', compact('data'));
    }
    public function MyProfileView() 
    {
        $data = User::find(Auth::user()->id);
        $states = State::all();
        return view('users/myprofile', compact('data','states'));
    }

    public function update(Request $request)
    {
        $rules = array (
            'firstname' => 'required'
            ,'email' => 'required|string|email|max:255|unique:users,email,'.$request->id
            ,'mobile_no' => 'required|numeric|digits:10|unique:users,mobile_no,'.$request->id
            ,'gender' => 'required'
        );


        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails()) 
            return Response::json(array('success' => 0,'message' => $validator->getMessageBag()->toArray()));
        else {

            $firstname = $request['firstname'];
            $lastname = $request['lastname'];
            $email = $request['email'];
            $mobile_no = $request['mobile_no'];
            $character_name = $request['character_name'];
            $gender = $request['gender'];
            $status = $request['status'];
            $paymentupi = $request['paymentupi'];
            $id = $request['id'];

            $user = User::find($id);
            $user->firstname = $firstname;
            $user->lastname = $lastname;
            $user->email = $email;
            $user->mobile_no = $mobile_no;
            $user->character_name = $character_name;
            $user->gender = $gender;
            $user->paymentupi = $paymentupi;
            $user->status = ($status == 'on') ? 1 : 0;
            $user->save();
            
            return Response::json(array('success' => 1,'message'=>'User update successfully.','data' => $user));
        }
    }
    public function updateUserProfile(Request $request)
    {
        $rules = array (
            'firstname' => 'required'
            ,'email' => 'required|string|email|max:255|unique:users,email,'.Auth::user()->id
            ,'gender' => 'required'
        );
        if(!empty($request['password']))
        {   
            $rules['password'] = 'required|min:6|confirmed';
        }
        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails()) 
            return Response::json(array('success' => 0,'message' => $validator->getMessageBag()->toArray()));
        else {

            $firstname = $request['firstname'];
            $lastname = $request['lastname'];
            $email = $request['email'];
            $character_name = $request['character_name'];
            $gender = $request['gender'];
            $paymentupi = $request['paymentupi'];
            $country = $request['country'];
            $state = $request['state'];
            $city = $request['city'];
            $area = $request['area'];
            $password = $request['password'];

            $user = User::find(Auth::user()->id);
            $user->firstname = $firstname;
            $user->lastname = $lastname;
            $user->email = $email;
            $user->character_name = $character_name;
            $user->gender = $gender;
            $user->paymentupi = $paymentupi;
            $user->country = $country;
            $user->state = $state;
            $user->city = $city;
            $user->area = $area;
            if(!empty($request['password']))
            {   
                $user->password = Hash::make($password);
            }
            $user->save();

            return Response::json(array('success' => 1,'message'=>'Profile update successfully.','data' => $user));
        }
    }

    public function destroy(Request $request)
    {
        User::find($request->id)->delete();
        return response()->json();
    }

    public function ChangePassword(Request $request)
    {
        $rules = array (
            'user_id' => 'required'
            ,'password' => 'required|min:6|confirmed'
        );


        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails()) 
            return Response::json(array('success' => 0,'message' => $validator->getMessageBag()->toArray()));
        else {

            $user_id = $request['user_id'];
            $password = $request['password'];

            $user = User::find($user_id);
            $user->password = Hash::make($password);
            $user->save();
            
            return Response::json(array('success' => 1,'message'=>'User password update successfully.','data' => $user));
        }
    }
    public function ChangeRole(Request $request)
    {
        $rules = array (
            'user_id' => 'required'
            ,'role' => 'required'
        );


        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails()) 
            return Response::json(array('success' => 0,'message' => $validator->getMessageBag()->toArray()));
        else {

            $user_id = $request['user_id'];
            $role = $request['role'];

            DB::table('role_user')
            ->where('user_id', $user_id)
            ->update(['role_id' => $role]);
            
            return Response::json(array('success' => 1,'message'=>'User role update successfully.','data' => []));
        }
    }

    public function UserTransaction(Request $request)
    {
        $PaymentTransactions = PaymentTransaction::where('user_id',$request->user_id)->get();
        return DataTables::of($PaymentTransactions)

        ->addColumn('event_name', function ($getTransaction) {

            $eventLink = "<a href='".route('event-details', ["id"=>$getTransaction->event_id])."' class='purple'>".$getTransaction->events->event_name."</a>";
            return $eventLink;
        })
        ->rawColumns(['event_name'])

        ->make(true);
    }

    public function getCities(Request $request)
    {
        $rules = array(
            'state' => 'required'
        );


        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails()) 
            return Response::json(array('success' => 0,'message' => $validator->getMessageBag()->toArray()));
        else {

            $state = $request['state'];
            $CityList = City::where('state_id',$state)->get();
            
            return Response::json(array('success' => 1,'message'=>'City list successfully.','data' => $CityList));
        }
    }
}