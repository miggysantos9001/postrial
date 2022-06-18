<?php

namespace App\Http\Controllers;

use App\Product;
use App\Order;
use App\Order_detail;
use App\Term;
use App\Client;

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

class SVIBookingController extends Controller
{
	public function __construct(){
	    $this->middleware('auth');
	}
	
	public function index(){
		$orders = Order::orderBy('date','DESC')->get();
		return view('orders.index',compact('orders'));
	}

    public function create(){
    	$date = Carbon::now();	
    	
        $products = Product::orderBy('position')->get();
        $terms = Term::orderBy('name')->get()->pluck('name','id');
        $clients = Client::orderBy('name')->get()->pluck('name','id');
    	return view('orders.create',compact('products','terms','clients'));
    }

    public function store(){
    	$validator = Validator::make(Request::all(), [
		    'date'						=>	'required',
		    'client_id'					=>	'required',
		    'term_id'					=>	'required',
		    'product_id.*'				=>	'required',
		    'heads.*'					=>	'required|numeric',
		    'weight.*'					=>	'required',
		    'unit_price.*'				=>	'required',
		    'total_price.*'				=>	'required',
		],
		[
			'client_id.required'		=>	'Please select client',
			'term_id.required'			=>	'Please select term',
			'product_id.*.required'		=>	'Please select product',
			'heads.*.required'			=>	'Please enter valid heads',
			'weight.*.required'			=>	'Please enter valid weight',
			'unit_price.*.required'		=>	'Please enter valid unit price',
			'total_price.*.required'	=>	'Please enter valid total price',
		]);

		if ($validator->fails()) {
		    return redirect()->back()
                ->withErrors($validator)
                ->withInput();
		}

		$svi = [
			'date'			=>		Request::get('date'),
			'term_id'		=>		Request::get('term_id'),
			'check_number'	=>		Request::get('check_number'),
			'due_date'		=>		Request::get('due_date'),
			'client_id'		=>		Request::get('client_id'),
			'cashbond'		=>		Request::get('cashbond'),
		];

		$order_id = Order::create($svi)->id;

		foreach(Request::get('product_id') as $key => $value){
			$pos = Product::where('id',$value)->first();
			$svi_details = [
				'order_id'		=>		$order_id,
				'client_id'		=>		Request::get('client_id'),
				'date'			=>		Request::get('date'),
				'product_id'	=>		$value,
				'position'		=>		$pos->position,
				'heads'			=>		Request::get('heads')[$key],
				'weight'		=>		Request::get('weight')[$key],
				'unit_price'	=>		Request::get('unit_price')[$key],
				'total_price'	=>		Request::get('total_price')[$key],
			];

			Order_detail::create($svi_details);
		}

		notify()->success('Order Entry Created');
    	return redirect()->back();
    }

    public function edit($id){
    	$order = Order::find($id);
    	$terms = Term::orderBy('name')->get()->pluck('name','id');
    	$clients = Client::orderBy('name')->get()->pluck('name','id');
    	return view('orders.edit',compact('order','terms','clients'));
    }

    public function update($id){
    	$order = Order::find($id);
    	$validator = Validator::make(Request::all(), [
		    'date'						=>	'required',
		    'client_id'					=>	'required',
		    'term_id'					=>	'required',
		    'product_id.*'				=>	'required',
		    'heads.*'					=>	'required|numeric',
		    'weight.*'					=>	'required',
		    'unit_price.*'				=>	'required',
		    'total_price.*'				=>	'required',
		],
		[
			'client_id.required'		=>	'Please select client',
			'term_id.required'			=>	'Please select term',
			'product_id.*.required'		=>	'Please select product',
			'heads.*.required'			=>	'Please enter valid heads',
			'weight.*.required'			=>	'Please enter valid weight',
			'unit_price.*.required'		=>	'Please enter valid unit price',
			'total_price.*.required'	=>	'Please enter valid total price',
		]);

		if ($validator->fails()) {
		    return redirect()->back()
                ->withErrors($validator)
                ->withInput();
		}

		$order->update([
			'date'			=>		Request::get('date'),
			'term_id'		=>		Request::get('term_id'),
			'check_number'	=>		Request::get('check_number'),
			'due_date'		=>		Request::get('due_date'),
			'client_id'		=>		Request::get('client_id'),
			'cashbond'		=>		Request::get('cashbond'),
		]);

		$order->details()->delete();

		foreach(Request::get('product_id') as $key => $value){
			$pos = Product::where('id',$value)->first();
			$svi_details = [
				'order_id'		=>		$order->id,
				'client_id'		=>		Request::get('client_id'),
				'date'			=>		Request::get('date'),
				'product_id'	=>		$value,
				'position'		=>		$pos->position,
				'heads'			=>		Request::get('heads')[$key],
				'weight'		=>		Request::get('weight')[$key],
				'unit_price'	=>		Request::get('unit_price')[$key],
				'total_price'	=>		Request::get('total_price')[$key],
			];

			Order_detail::create($svi_details);
		}

		notify()->success('Order Entry Updated');
    	return redirect()->back();
    }
}
