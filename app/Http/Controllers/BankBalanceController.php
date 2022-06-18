<?php

namespace App\Http\Controllers;

use App\Product;
use App\Booking;
use App\Booking_detail;
use App\Booking_list;
use App\Start_balance;

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

class BankBalanceController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
    	$start = Start_balance::first();
    	return view('balances.index',compact('start'));
    }

    public function store(){
    	$start = Start_balance::first();
    	$validator = Validator::make(Request::all(), [
		    'balance'		=>	'required',
		]);

		if ($validator->fails()) {
		    return redirect()->back()
                ->withErrors($validator)
                ->withInput();
		}

		Start_balance::where('id',$start->id)->update([
			'balance'		=>		Request::get('balance'),
		]);

		notify()->success('Bank Balance Updated');
    	return redirect()->back();
    }
}
