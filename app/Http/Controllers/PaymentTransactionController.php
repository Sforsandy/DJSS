<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use App\Event;
use App\PayTm;
use App\PaymentTransaction;
use App\EventJoin;
use Auth;
use Session;
use Validator;
use Toastr;
use Response;
use DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
class PaymentTransactionController extends Controller {
	
	public function __construct()
    {
        $this->middleware('auth');
    }

    public function showTransactions()
    {
        return view('transactions/index');
    }

    public function data(Request $request)
    {
    	$StartDate = $request['StartDate'];
    	$EndDate = $request['EndDate'];
    	if(Auth::user()->hasRole('admin'))
    	{
    		// $PaymentTransactions = PaymentTransaction::whereNotNull('status')->get();
    		$PaymentTransactionsQry = PaymentTransaction::whereNotNull('status');
    		if($StartDate != '' && $EndDate !=  '')
    		{
    			$EndDate =	Carbon::createFromFormat('Y-m-d', $EndDate)->addDays(1)->toDateTimeString();
    			$PaymentTransactionsQry->whereBetween('txn_date',[$StartDate,$EndDate]);
    		}
    		$PaymentTransactionsQry->with('events');
    		$PaymentTransactions = $PaymentTransactionsQry->get();
    	}else
    	{
    		$PaymentTransactionsQry = PaymentTransaction::whereNotNull('status')
    		->where('user_id',Auth::user()->id);
    		if($StartDate != '' && $EndDate !=  '')
    		{
    			$EndDate =	Carbon::createFromFormat('Y-m-d', $EndDate)->addDays(1)->toDateTimeString();
    			$PaymentTransactionsQry->whereBetween('txn_date',[$StartDate,$EndDate]);
    		}
    		$PaymentTransactionsQry->with('events');
    		$PaymentTransactions = $PaymentTransactionsQry->get();
    	}
        return DataTables::of($PaymentTransactions)

        ->addColumn('event_name', function ($getTransaction) {
        	$data  =  $getTransaction->events;
        	if(isset($data))
            {
                $eventLink = "<a href='".route('event-details', ["id"=>$getTransaction->event_id])."' class='purple'>".$getTransaction->events->event_name."</a>";
            	return $eventLink;
            }
            else{return '-';}
        	
        })
        ->rawColumns(['event_name'])

        ->make(true);
    }

    public function viewPaymentStatus($id)
    {
    	$data = PaymentTransaction::where('id',$id)->with('events')->first();
    	if($data->user_id != Auth::user()->id)
    	{
    		return view('errors/403');

    	}
    	return view('transactions/paymentstatus', compact('data'));
    }

    public function PaymentResponse(Request $request)
    {
    	$paytmChecksum = "";
    	$paramList = array();
    	$isValidChecksum = "FALSE";
    	$paramList = $_POST;

    	$uri_path = $_SERVER['REQUEST_URI']; 
    	$uri_parts = explode('/', $uri_path);
    	$transaction_id = end($uri_parts);
    	$paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : ""; 
    	$PayTm = new PayTm();
		$isValidChecksum = $PayTm->verifychecksum_e($paramList,config('app.PAYTM_MERCHANT_KEY'), $paytmChecksum); //will return TRUE or FALSE string.
		$return_array["IS_CHECKSUM_VALID"] = $isValidChecksum ? "Y" : "N";
		// unset($return_array["CHECKSUMHASH"]);

		$transactionData = PaymentTransaction::find($transaction_id);
		$event_id  = $transactionData->event_id;
		if($isValidChecksum)
		{	
			$eventtransaction = PaymentTransaction::find($transaction_id);
			$eventtransaction->txn_id			=	isset($paramList['TXNID']) ? $paramList['TXNID'] : '';
			$eventtransaction->payment_mode		=	isset($paramList['PAYMENTMODE']) ? $paramList['PAYMENTMODE'] : '';
			$eventtransaction->txn_amount		=	isset($paramList['TXNAMOUNT']) ? $paramList['TXNAMOUNT'] : '';
			$eventtransaction->currency			=	isset($paramList['CURRENCY']) ? $paramList['CURRENCY'] : '';
			$eventtransaction->txn_date			=	isset($paramList['TXNDATE']) ? $paramList['TXNDATE'] : date('Y-m-d H:i:s');
			$eventtransaction->status			=	isset($paramList['STATUS']) ? $paramList['STATUS'] : '';
			$eventtransaction->resp_code		=	isset($paramList['RESPCODE']) ? $paramList['RESPCODE'] : '';
			$eventtransaction->resp_msg			=	isset($paramList['RESPMSG']) ? $paramList['RESPMSG'] : '';
			$eventtransaction->gateway_name		=	isset($paramList['GATEWAYNAME']) ? $paramList['GATEWAYNAME'] : '';
			$eventtransaction->bank_txn_id		=	isset($paramList['BANKTXNID']) ? $paramList['BANKTXNID'] : '';
			$eventtransaction->bank_name		=	isset($paramList['BANKNAME']) ? $paramList['BANKNAME'] : '';
			$eventtransaction->check_sum_hash	=	isset($paramList['CHECKSUMHASH']) ? $paramList['CHECKSUMHASH'] : '';
			$eventtransaction->full_response	=	json_encode($paramList);
			$eventtransaction->event_type		=	1;
			$eventtransaction->save();
			if($paramList['STATUS'] == 'TXN_SUCCESS')
			{
				$joinExist = EventJoin::where('event_id',$event_id)->where('user_id',Auth::user()->id)->count();
				if($joinExist > 0)
				{
					Toastr::success("Already join this event.", $title = null, $options = []);
					return redirect()->route('event-details', ['id' => $event_id]);
				}
				$eventData = Event::find($event_id);
				$chkCapacity = $eventData->capacity;
				$totalJoin = EventJoin::where('event_id',$event_id)->count();
				if($eventData->created_by == Auth::user()->id)
				{
					Toastr::error("Can't join self event.", $title = null, $options = []);
					return redirect()->route('event-details', ['id' => $event_id]);
				}
				if($eventData->schedule_datetime < date("Y-m-d H:i:s")) 
				{
					Toastr::error("Event is ongoing or past you can't join.", $title = null, $options = []);
					return redirect()->route('event-details', ['id' => $event_id]);
				}
				if($eventData->status == 1)
				{
					Toastr::success("Event is ongoing you can't join.", $title = null, $options = []);
					return redirect()->route('event-details', ['id' => $event_id]);
				}
				if($eventData->status == 2)
				{
					Toastr::success("Event is past you can't join.", $title = null, $options = []);
					return redirect()->route('event-details', ['id' => $event_id]);
				}
				if($chkCapacity <= $totalJoin)
				{
					Toastr::success("Event full you can not join now.", $title = null, $options = []);
					return redirect()->route('event-details', ['id' => $event_id]);
				}

				$eventjoin = new EventJoin();
				$eventjoin->user_id = Auth::user()->id;
				$eventjoin->event_id = $event_id;
				$eventjoin->joined_date = date("Y-m-d H:i:s");
				$eventjoin->save();
				Toastr::success('Event join successfully.', $title = null, $options = []);
				return redirect()->route('paymentstatus', [$transaction_id]);
				// return redirect()->route('event-details', ['id' => $event_id]);
			}
			else
			{
				Toastr::error('Payment transaction fail.', $title = null, $options = []);
				// return redirect()->route('event-details', ['id' => $event_id]);
				return redirect()->route('paymentstatus', [$transaction_id]);
			}
		}else
		{
			Toastr::error('Something went wrong.', $title = null, $options = []);
			return redirect()->route('event-details', ['id' => $event_id]);
		}
    }

}