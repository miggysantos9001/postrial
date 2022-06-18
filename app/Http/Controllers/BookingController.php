<?php

namespace App\Http\Controllers;

use App\Product;
use App\Booking;
use App\Booking_detail;
use App\Booking_list;
use App\Customer;
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

class BookingController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    
	public function index(){
		$bookings = Booking::orderBy('date','DESC')->get();
        $clients = Client::orderBy('name')->get()->pluck('name','id');
        $customers = Customer::orderBy('name')->get()->pluck('name','id');
		return view('bookings.index',compact('bookings','customers','clients'));
	}

    public function create_booking_form(){
        $validator = Validator::make(Request::all(), [
            'date'                      =>  'required',
            'client_id'                 =>  'required',
            'customer_id'               =>  'required|array|min:1',
        ],
        [
            'date.required'             =>  'Date Required',
            'client_id.required'        =>  'Please select client',
            'customer_id.required'      =>  'Please select at least one customer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $date = Request::get('date');
        $client = Request::get('client_id');

        $list = [];
        foreach(Request::get('customer_id') as $key => $value){
            $list[] = $value;
        }

        $checkDate = Booking::where('date',$date)->where('client_id',$client)->first();

        if($checkDate != NULL){
            Session::flash('delete_message', 'Customer Booking Already Exists!'); 
            return redirect('bookings');
        }else{

            $products = Product::orderBy('position')->get();
            $customers = Customer::whereIn('id',$list)->orderBy('name')->get();

            return view('bookings.create',compact('date','products','customers','client'));
        }
        
    }

    public function create(){
    	//$date = Carbon::now();
        $date = '2020-01-16';
        $products = Product::orderBy('position')->get();
        $customers = Customer::orderBy('name')->get();
    	return view('bookings.create',compact('date','products','customers'));
    }

    public function store(){

        $booking_id = Booking::create([
            'date'          =>      Request::get('date'),
            'client_id'     =>      Request::get('client_id'),
        ])->id;

    	foreach (Request::all() as $key => $value) {
    		if(substr($key, 0,3) == 'qty'){
    			$temp = explode('_', $key);
    			
    			$customer_id = $temp[1];
    			$product_id= $temp[2];

                $check = Product::where('id',$temp[2])->first();

                
                if($check->isHead == 1){
                    $heads = $value;
                    $weight = 0.00;
                }else{
                    $heads = 0.00;
                    $weight = $value;
                }
                
                $booking_details = [
                    'booking_id'        =>          $booking_id,
                    'client_id'         =>          Request::get('client_id'),
                    'date'              =>          Request::get('date'),
                    'customer_id'       =>          $temp[1],
                    'product_id'        =>          $temp[2],
                    'heads'             =>          $heads,  
                    'weight'            =>          $weight,
                ];

                Booking_detail::create($booking_details);
    		}
    	}

        notify()->success('Customer Booking Entry Created');
        return redirect('bookings');
    }

    public function edit($id){
        $booking = Booking::find($id);
        $list = [];

        foreach($booking->details as $bd){
            $list[] = $bd->customer_id;
        }

        $customers = Customer::whereIn('id',$list)->orderBy('name')->get();
        $products = Product::orderBy('position')->get();
        return view('bookings.edit',compact('booking','customers','products'));
    }

    public function update($id){
        $booking = Booking::find($id);
        $booking->update([
            'date'          =>      Request::get('date'),
            'client_id'     =>      $booking->client_id,
        ]);

        foreach (Request::all() as $key => $value) {
            if(substr($key, 0,3) == 'qty'){
                $temp = explode('_', $key);
                
                $customer_id = $temp[1];
                $product_id= $temp[2];
                $booking_detail_id= $temp[3];

                $check = Product::where('id',$temp[2])->first();

                if($check->isHead == 1){
                    $heads = $value;
                    $weight = 0.00;
                }else{
                    $heads = 0.00;
                    $weight = $value;
                }
                                
                $booking_details = [
                    'date'              =>          Request::get('date'),
                    'client_id'         =>          $booking->client_id,
                    'customer_id'       =>          $temp[1],
                    'product_id'        =>          $temp[2],
                    'heads'             =>          $heads,  
                    'weight'            =>          $weight,
                ];

                Booking_detail::where('id',$booking_detail_id)->update($booking_details);
            }
        }

        notify()->success('Customer Booking Entry Updated');
        return redirect()->back();
    }

    public function additional($id){
        $booking = Booking::find($id);

        $validator = Validator::make(Request::all(), [
            'addcust'               =>  'required|array|min:1',
        ],
        [
            'addcust.required'      =>  'Please select at least one customer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $date = $booking->date;
        $list = [];

        foreach(Request::get('addcust') as $key => $value){
            foreach($booking->details as $bd){
                if($bd->customer_id != $value){
                    $list[] = $value;
                }
            }
        }

        $products = Product::orderBy('position')->get();
        $customers = Customer::whereIn('id',$list)->orderBy('name')->get();

        return view('bookings.additional',compact('date','products','customers','booking'));
    }

    public function post_additional($id){
        $booking = Booking::find($id);

        /*if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }*/

        foreach (Request::all() as $key => $value) {
            if(substr($key, 0,3) == 'qty'){
                $temp = explode('_', $key);
                
                $customer_id = $temp[1];
                $product_id= $temp[2];

                $check = Product::where('id',$temp[2])->first();

                
                if($check->isHead == 1){
                    $heads = $value;
                    $weight = 0.00;
                }else{
                    $heads = 0.00;
                    $weight = $value;
                }
                
                
                $booking_details = [
                    'booking_id'        =>          $booking->id,
                    'client_id'         =>          $booking->client_id,
                    'date'              =>          $booking->date,
                    'customer_id'       =>          $temp[1],
                    'product_id'        =>          $temp[2],
                    'heads'             =>          $heads,  
                    'weight'            =>          $weight,
                ];

                Booking_detail::create($booking_details);
            }
        }

        notify()->success('Customer Booking Entry Updated');
        return redirect()->action('BookingController@index');
    }

    public function show($id){
        $booking = Booking::find($id);

        return view('bookings.show',compact('booking'));
    }

    public function delete($id){
        $booking = Booking::find($id);
        $booking->details()->delete();
        \App\Booking_list::where('booking_id',$booking->id)->delete();
        $booking->delete();
        notify()->success('Customer Booking Entry Deleted');
        return redirect()->action('BookingController@index');
    }

    public function view_booking_details($booking_id,$customer_id){
        $booking = Booking::find($booking_id);
        $customer = Customer::find($customer_id);
        $details = Booking_detail::where('booking_id',$booking_id)->where('customer_id',$customer_id)->get();
        
        return view('bookings.details',compact('booking','details','customer'));
    }

    public function post_booking_details($booking_id,$customer_id){
        $booking = Booking::find($booking_id);
        $customer = Customer::find($customer_id);

        $totalAmount = $totalWeight = 0;
        foreach (Request::get('heads') as $key => $value){
            $booking_details = [
                'heads'             =>          $value,  
                'weight'            =>          Request::get('weight')[$key],  
                'unit_price'        =>          Request::get('unit_price')[$key],
                'total_price'       =>          Request::get('total_price')[$key],
            ];

            $totalAmount +=  Request::get('total_price')[$key];
            $totalWeight +=  Request::get('weight')[$key];

            Booking_detail::where('id',Request::get('id')[$key])->update($booking_details);  
        }

        Booking_list::updateOrCreate([
            'booking_id'        =>      $booking->id,
            'customer_id'       =>      $customer->id,
        ],[
            'total_weights'     =>      $totalWeight,
            'total_price'       =>      $totalAmount,
        ]);

        notify()->success('Customer Booking Entry Updated');
        return redirect()->back();
    }

    public function delete_booking_details($booking_id,$customer_id){
        $booking = Booking::find($booking_id);
        $customer = Customer::find($customer_id);
        Booking_detail::where('booking_id',$booking->id)->where('customer_id',$customer->id)->delete();

        notify()->success('Customer Booking Entry Deleted');
        return redirect()->back();
    }
}
