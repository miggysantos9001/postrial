<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Customergroup;
use App\Customer_pricing;
use App\Product;
use App\Booking_list;

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

class CustomerController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    
    public function index(){
    	$customers = Customer::orderBy('name')->get();
    	$groups = Customergroup::orderBy('name')->get()->pluck('name','id');
    	return view('customers.index',compact('customers','groups'));
    }

    public function store(){
    	$validator = Validator::make(Request::all(), [
		    'name'						=>	'required|unique:customers',
            'code'                      =>  'required|unique:customers',
		    'balance'					=>	'required',
		],
		[
		    'name.required'     		=>	'Customer Name Required',
            'code.required'             =>  'Customer Code Required',
		    'balance.required'     		=>	'Customer Balance Required',
		]);

		if ($validator->fails()) {
		    return redirect()->back()
                ->withErrors($validator)
                ->withInput();
		}

		Customer::create(Request::all());

		notify()->success('Customer Entry Created');
    	return redirect()->back();
    }

    public function update($id){
    	$customer = Customer::find($id);
    	$validator = Validator::make(Request::all(), [
		    'name'						=>	"required|unique:customers,name,$customer->id,id",
            'code'                      =>  "required|unique:customers,code,$customer->id,id",
		    'balance'					=>	'required',
		],
		[
		    'name.required'     		=>	'Customer Name Required',
            'code.required'             =>  'Customer Code Required',
		    'balance.required'     		=>	'Customer Balance Required',
		]);

		if ($validator->fails()) {
		    return redirect()->back()
                ->withErrors($validator)
                ->withInput();
		}

		$customer->update(Request::all());

		notify()->success('Customer Entry Updated');
    	return redirect()->back();
    }	

    public function view_pricing($id){
    	$customer = Customer::find($id);
    	$products = Product::orderBy('position')->get();

    	return view('customers.pricing',compact('customer','products'));
    }

    public function post_pricing($id){
    	$customer = Customer::find($id);

    	foreach(Request::get('product_id') as $key => $value){
    		$pricing = Customer_pricing::updateOrCreate([
    			'customer_id'		=>		$customer->id,
    			'product_id'		=>		$value,
    		],[
    			'pricing'			=>		Request::get('pricing')[$key],
    		]);
    	}

    	notify()->success('Customer Pricing Entry Updated');
    	return redirect()->back();
    }

    public function view_account($id){
        $customer = Customer::find($id);
        $lists = $customer->lists()->orderBy('booking_id','DESC')->get();

        return view('customers.account',compact('customer','lists'));
    }
}
