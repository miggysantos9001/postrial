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

class OrderController extends Controller
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
    	$voucher_data = Order::where('date',$date->toDateString())->count() + 1;
        $control_num = $date->year.'-'.$date->format('m').$date->format('d').'-'.$voucher_data;
        $products = Product::orderBy('name')->get()->pluck('name','id');
        $terms = Term::orderBy('name')->get()->pluck('name','id');
        $clients = Client::orderBy('name')->get()->pluck('name','id');
    	return view('orders.create',compact('control_num','products','terms','clients'));
    }

    public function store(){
    	$validator = Validator::make(Request::all(), [
		    'date'						=>	'required',
		    'client_id'					=>	'required',
		    'order_number'				=>	'required',
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

		$order = [
			'date'				=>		Request::get('date'),
			'order_number'		=>		Request::get('order_number'),
			'term_id'			=>		Request::get('term_id'),
			'client_id'			=>		Request::get('client_id'),
		];

		$order_id = Order::create($order)->id;

		foreach(Request::get('product_id') as $key => $value){
			$details = [
				'order_id'		=>		$order_id,
				'product_id'	=>		$value,
				'heads'			=>		Request::get('heads')[$key],
				'weight'		=>		Request::get('weight')[$key],
				'unit_price'	=>		Request::get('unit_price')[$key],
				'total_price'	=>		Request::get('total_price')[$key],
			];

			Order_detail::create($details);
		}

		notify()->success('Order Entry Created');
    	return redirect()->back();
    }

    public function edit($id){
    	$order = Order::find($id);
    	$products = Product::orderBy('name')->get()->pluck('name','id');
    	$terms = Term::orderBy('name')->get()->pluck('name','id');
    	return view('orders.edit',compact('order','products','terms'));
    }

    public function update($id){
    	$order = Order::find($id);
    	$validator = Validator::make(Request::all(), [
		    'date'						=>	'required',
		    'order_number'				=>	'required',
		    'term_id'					=>	'required',
		],[
			'term_id.required'			=>	'Please select term',	
		]);

		if ($validator->fails()) {
		    return redirect()->back()
                ->withErrors($validator)
                ->withInput();
		}

		$data = [
			'date'				=>		Request::get('date'),
			'order_number'		=>		Request::get('order_number'),
			'term_id'			=>		Request::get('term_id'),
		];

		Order::where('id',$order->id)->update($data);

		foreach(Request::get('product_id') as $key => $value){
			if(!empty($value)){
				$details = [
					'order_id'		=>		$order->id,
					'product_id'	=>		$value,
					'heads'			=>		Request::get('heads')[$key],
					'weight'		=>		Request::get('weight')[$key],
					'unit_price'	=>		Request::get('unit_price')[$key],
					'total_price'	=>		Request::get('total_price')[$key],
				];

				Order_detail::create($details);
			}
		}

		notify()->success('Order Entry Updated');
    	return redirect()->back();
    }

    public function delete($id){
    	$order = Order::find($id);
    	$order->details()->delete();
    	$order->delete();
    	notify()->success('Order Entry Deleted');
    	return redirect()->back();
    }

    public function update_item($id){
    	$detail = Order_detail::find($id);
    	$detail->update([
    		'product_id'	=>		Request::get('product_id'),
			'heads'			=>		Request::get('heads'),
			'weight'		=>		Request::get('weight'),
			'unit_price'	=>		Request::get('unit_price'),
			'total_price'	=>		Request::get('unit_price') * Request::get('weight'),
    	]);
    	notify()->success('Order Detail Updated');
    	return redirect()->back();
    }

    public function delete_item($id){
    	$detail = Order_detail::find($id);
    	$detail->delete();

    	$allorders = Order_detail::where('order_id',$detail->order_id)->count();
    	if($allorders == 0){
    		Order::where('id',$detail->order_id)->delete();
    	}

    	notify()->success('Order Detail Deleted');
    	if($allorders == 0){
    		return redirect('orders');
    	}else{
    		return redirect()->back();
    	}
    }
}
