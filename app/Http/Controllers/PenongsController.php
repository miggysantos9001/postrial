<?php

namespace App\Http\Controllers;

use App\Product;
use App\Booking;
use App\Booking_detail;
use App\Booking_list;
use App\Customer;

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

class PenongsController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
		$bookings = Booking::where('isPenong',1)->orderBy('date','DESC')->get();
        $customers = Customer::where('group_id','=',3)->orderBy('name')->get()->pluck('name','id');
		return view('penongs.index',compact('bookings','customers'));
	}

	public function create_booking_form(){
        $validator = Validator::make(Request::all(), [
            'date'                      =>  'required',
            'customer_id'               =>  'required|array|min:1',
        ],
        [
            'date.required'             =>  'Date Required',
            'customer_id.required'      =>  'Please select at least one customer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $date = Request::get('date');

        $list = [];
        foreach(Request::get('customer_id') as $key => $value){
            $list[] = $value;
        }

        $checkDate = Booking::where('date',$date)->first();

        if($checkDate != NULL){
            Session::flash('delete_message', 'Customer Booking Already Exists!'); 
            return redirect('bookings');
        }else{
        	$prods = array('1','2','3','4','14','24');
            $products = Product::whereIn('id',$prods)->orderBy('position')->get();
            $customers = Customer::whereIn('id',$list)->orderBy('name')->get();
            return view('penongs.create',compact('date','products','customers'));
        }
        
    }

    public function store(){
        $booking_id = Booking::create([
            'date'      =>      Request::get('date'),
            'isPenong'	=>		1,
        ])->id;

    	foreach (Request::all() as $key => $value) {
    		if(substr($key, 0,3) == 'qty'){
    			$temp = explode('_', $key);
    			
    			$customer_id = $temp[1];
    			$product_id= $temp[2];

                $check = Product::where('id',$temp[2])->first();

                if($check->bags == 1){
                    $heads = 0.00;
                    $weight = $value;
                }else{
                    $heads = $value;
                    $weight = 0.00;
                }
    			
                $booking_details = [
                    'booking_id'        =>          $booking_id,
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
        return redirect('penongs-bookings');
    }

    public function edit($id){
        $booking = Booking::find($id);
        $list = [];

        foreach($booking->details as $bd){
            $list[] = $bd->customer_id;
        }

        $customers = Customer::whereIn('id',$list)->orderBy('name')->get();
        $prods = array('1','2','3','4','14','24');
        $products = Product::whereIn('id',$prods)->orderBy('position')->get();
        return view('penongs.edit',compact('booking','customers','products'));
    }

    public function update($id){
        $booking = Booking::find($id);
        $booking->update([
            'date'  =>  Request::get('date'),
        ]);

        foreach (Request::all() as $key => $value) {
            if(substr($key, 0,3) == 'qty'){
                $temp = explode('_', $key);
                
                $customer_id = $temp[1];
                $product_id= $temp[2];
                $booking_detail_id= $temp[3];

                $check = Product::where('id',$temp[2])->first();

                if($check->bags == 1){
                    $heads = 0.00;
                    $weight = $value;
                }else{
                    $heads = $value;
                    $weight = 0.00;
                }
                
                $booking_details = [
                    'date'              =>          Request::get('date'),
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
        
        $date = $booking->date;
        $list = [];

        foreach(Request::get('addcust') as $key => $value){
            foreach($booking->details as $bd){
                if($bd->customer_id != $value){
                    $list[] = $value;
                }
            }
        }

        $prods = array('1','2','3','4','14','24');
        $products = Product::whereIn('id',$prods)->orderBy('position')->get();
        $customers = Customer::whereIn('id',$list)->orderBy('name')->get();

        return view('penongs.additional',compact('date','products','customers','booking'));
    }

    public function post_additional($id){
        $booking = Booking::find($id);

        foreach (Request::all() as $key => $value) {
            if(substr($key, 0,3) == 'qty'){
                $temp = explode('_', $key);
                
                $customer_id = $temp[1];
                $product_id= $temp[2];

                $check = Product::where('id',$temp[2])->first();

                if($check->bags == 1){
                    $heads = 0.00;
                    $weight = $value;
                }else{
                    $heads = $value;
                    $weight = 0.00;
                }
                
                $booking_details = [
                    'booking_id'        =>          $booking->id,
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
        return redirect()->action('PenongsController@index');
    }

    public function show($id){
        $booking = Booking::find($id);

        return view('penongs.show',compact('booking'));
    }

    public function delete($id){

    }

    public function view_booking_details($booking_id,$customer_id){
        $booking = Booking::find($booking_id);
        $customer = Customer::find($customer_id);
        $details = Booking_detail::where('booking_id',$booking_id)->where('customer_id',$customer_id)->get();
        
        return view('penongs.details',compact('booking','details','customer'));
    }

    public function post_booking_details($booking_id,$customer_id){
        $booking = Booking::find($booking_id);
        $customer = Customer::find($customer_id);

        $totalAmount = $totalWeight = 0;
        foreach (Request::get('heads') as $key => $value){
            $booking_details = [
                //'heads'             =>          $value,  
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
}
