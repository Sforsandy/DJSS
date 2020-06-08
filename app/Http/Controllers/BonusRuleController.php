<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use App\BonusRule;
use Auth;
use Session;
use Validator;
use Toastr;
use Response;
use DataTables;
use Illuminate\Support\Facades\Input;
class BonusRuleController extends Controller {
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
        return view('bonus_rules/index');
    }
    public function create()
    {
        return view('bonus_rules/create');
    }

    public function data()
    {
        $BonusRules = BonusRule::all();
        return DataTables::of($BonusRules)
        ->addColumn('action', function ($getBonusRule) {

            $editBtn = "<a href='".route('bonus-rule.edit', $getBonusRule->id)."' class='purple'> <i class='fa fa-pencil'></i> </a>";
            $deleteBtn = '';
            
            $deleteBtn = "<a href='javascript:;' id='deleteUser' data-id='".$getBonusRule->id."' data-name='".$getBonusRule->rule."' onclick='if (confirm(\"Are you sure you want to delete ?\")) deleteRow(".$getBonusRule->id.") ' class='blue'> <i class='fa fa-trash'></i> </a>";
            return $editBtn.$deleteBtn;
        })
        ->rawColumns(['rule','status', 'action'])

        ->make(true);
    }


    public function store(Request $request)
    {
        $rules = array(
            'rule' => 'required|max:100|unique:bonus_rules,rule',
            'name' => 'required',
            'amount' => 'required'
        );


        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails()) 
            return Response::json(array('success' => 0,'message' => $validator->getMessageBag()->toArray()));
        else {

            $rule = $request['rule'];
            $amount = $request['amount'];
            $name = $request['name'];
            
            $BonusRule = new BonusRule();
            $BonusRule->rule = $rule;
            $BonusRule->name = $name;
            $BonusRule->amount = $amount;
            $BonusRule->save(); 
            
            
            return Response::json(array('success' => 1,'message'=>'Bonus rule added successfully.','data' => $BonusRule));
        }
    }

    public function edit($id) 
    {
        $data = BonusRule::find($id);
        return view('bonus_rules/edit', compact('data'));
    }

    public function update(Request $request)
    {
        $rules = array(
            'rule' => 'required|max:100|unique:bonus_rules,rule,'.$request->id,
            'name' => 'required',
            'amount' => 'required'
        );

        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails()) 
            return Response::json(array('success' => 0,'message' => $validator->getMessageBag()->toArray()));
        else {
            $rule = $request['rule'];
            $amount = $request['amount'];
            $name = $request['name'];
            $id = $request['id'];

            $BonusRule = BonusRule::find($id);
            $BonusRule->rule = $rule;
            $BonusRule->amount = $amount;
            $BonusRule->name = $name;
            $BonusRule->save();
            
            return Response::json(array('success' => 1,'message'=>'Bonus rule update successfully.','data' => $BonusRule));
        }
    }

    public function destroy(Request $request)
    {
        BonusRule::find($request->id)->delete();
        return response()->json();
    }
}