<?php

namespace App\Http\Controllers;

use App\Product;
use App\Booking;
use App\Booking_list;
use App\Customer;
use App\Payment;
use App\Payment_detail;

use Validator;
use Auth;
use PDF;
use DB;
use Session;
use Input;
use Request;
use DateTime;
use Hash;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    
    public function index(){
    	$customers = Customer::orderBy('name')->get()->pluck('name','id');
        $payments = Payment::orderBy('date','DESC')->get();
    	return view('payments.index',compact('customers','payments'));
    }

    public function store(){
    	$validator = Validator::make(Request::all(), [
		    'customer_id'				=>	'required',
		],[
			'customer_id.required'		=>	'Please select customer',
		]);

		if ($validator->fails()) {
		    return redirect()->back()
                ->withErrors($validator)
                ->withInput();
		}

		$customer = Customer::where('id',Request::get('customer_id'))->first();

		return view('payments.booking-list',compact('customer'));
    }

    public function store_payments($id){

        $validator = Validator::make(Request::all(), [
            'list_id'               =>  'required|array|min:1',
            'or_number'             =>  'required',
            'payment_mode'          =>   'required',
        ],[
            'list_id.required'      =>  'Please check booking transaction to be paid',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        if(Request::get('withTax') == 1){
            $tax = Request::get('amount') * .01;
        }else{
            $tax = 0.00;
        }

    	$payment_id = Payment::create([
    		'customer_id'		=>		$id,
    		'date'				=>		Request::get('date'),
    		'or_number'			=>		Request::get('or_number'),
    		'payment_mode'		=>		Request::get('payment_mode'),
			'amount'			=>		Request::get('amount'),
            'tax'               =>      $tax,
			'remarks'			=>		Request::get('remarks'),
    	])->id;

        $paidAmount = Request::get('amount');

    	foreach(Request::get('list_id') as $key => $value){

            if($paidAmount >= Request::get('total_price')[$key]){
                $payment = Request::get('total_price')[$key];
                $isPaid = 1;
                $paidAmount -= Request::get('total_price')[$key];
            }else{
                $payment = $paidAmount;
                $isPaid = 0;
            }

            $partialPayment = Booking_list::where('id',$value)->first();
            
            Booking_list::where('id',$value)->update([
                'isPaid'        =>      $isPaid,
                'paymentMade'   =>      $payment + $partialPayment->paymentMade,
                'tax'           =>      $tax,
                'datePaid'      =>      Request::get('date'),
                'payment_id'    =>      $payment_id,
            ]);

            Payment_detail::create([
                'customer_id'   =>      $partialPayment->customer_id,
                'booking_id'    =>      $partialPayment->booking_id,
                'payment_id'    =>      $payment_id,
            ]);

    	}

    	notify()->success('Payment Entry Created');
    	return redirect('payments');
    }

    public function update_payments($id){
        $list = Booking_list::find($id);

        if(Request::get('paymentMade') < $list->total_price){
            $isPaid = 0;
        }else{
            $isPaid = 1;
        }

        $updatePayment = [
            'paymentMade'       =>      Request::get('paymentMade'),
            'isPaid'            =>      $isPaid,
        ];

        $list->update($updatePayment);

        $bookingSumList = Booking_list::where('booking_id',$list->booking_id)->sum('paymentMade');

        /*if(Request::get('withTax') == 1){
            $tax = Request::get('paymentMade') * .01;
        }else{
            $tax = 0.00;
        }*/

        Payment::where('id',$list->payment_id)->update([
            //'tax'           =>      $tax,
            'amount'        =>      $bookingSumList,
        ]);

        notify()->success('Payment Entry Updated');
        return redirect('payments');
    }

    public function delete_payments($id){
        $payment = Payment::find($id);
        $pd = Payment_detail::where('payment_id',$id)->first();
        $blraw = Booking_list::where('booking_id',$pd->booking_id)
            ->where('customer_id',$pd->customer_id)
            ->first();

        /*UPDATE BOOKING LIST*/
        $bl = Booking_list::where('booking_id',$pd->booking_id)
            ->where('customer_id',$pd->customer_id)
            ->update([
                'paymentMade'       =>     $blraw->paymentMade - $payment->amount,
                'isPaid'            =>      0, 
            ]);

        Payment_detail::where('payment_id',$id)->delete();
        $payment->delete();

        notify()->success('Payment Entry Deleted');
        return redirect('payments');
    }

    public function show($id){
        $payment = Payment::find($id);

        return view('payments.show',compact('payment'));
    }

    public function update($id){
        $payment = Payment::find($id);

        if(Request::get('withTax') == 1){
            $tax = Request::get('amount') * .01;
        }else{
            $tax = 0.00;
        }

        $payment_id = Payment::where('id',$payment->id)->update([
            'date'              =>      Request::get('date'),
            'or_number'         =>      Request::get('or_number'),
            'payment_mode'      =>      Request::get('payment_mode'),
            'amount'            =>      Request::get('amount'),
            'tax'               =>      $tax,
            'remarks'           =>      Request::get('remarks'),
        ]);

        /*$customer_id = $payment->customer_id;
        $payment_id = $payment->id;

        $checkPayment = \App\Payment_detail::where('customer_id',$customer_id)
            ->where('payment_id',$payment_id)
            ->first();*/
        //if($checkPayment != NULL){
            \App\Booking_list::where('payment_id',$payment->id)->update([
                'paymentMade'      =>     Request::get('amount'), 
                'tax'              =>      $tax,
            ]);
        //}    
        

        notify()->success('Payment Entry Updated');
        return redirect()->back();

    }
}
