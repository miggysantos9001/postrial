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

class DonationController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        $clients = Client::orderBy('name')->get()->pluck('name','id');
        $donations = \App\Donation::orderBy('date','DESC')->get();
		return view('donations.index',compact('clients','donations'));
	}

	public function create_booking_form(){
        $validator = Validator::make(Request::all(), [
            'date'                      =>  'required',
            'client_id'                 =>  'required',
        ],
        [
            'date.required'             =>  'Date Required',
            'client_id.required'        =>  'Please select client',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $date = Request::get('date');
        $client = Request::get('client_id');

        $checkDate = \App\Donation::where('date',$date)->where('client_id',$client)->first();

        if($checkDate != NULL){
            Session::flash('delete_message', 'Donation Already Exists!'); 
            return redirect('donations');
        }else{

            $products = Product::orderBy('position')->get();

            return view('donations.create',compact('date','products','client'));
        }
        
    }

    public function store(){
    	$donation_id = \App\Donation::create([
            'date'          =>      Request::get('date'),
            'client_id'     =>      Request::get('client_id'),
        ])->id;

        foreach (Request::all() as $key => $value) {
            if(substr($key, 0,3) == 'qty'){
                $temp = explode('_', $key);
                
                $product_id= $temp[1];

                $check = Product::where('id',$temp[1])->first();

                
                if($check->isHead == 1){
                    $heads = $value;
                    $weight = 0.00;
                }else{
                    $heads = 0.00;
                    $weight = $value;
                }
                
                $donation_details = [
                    'donation_id'        =>         $donation_id,
                    'client_id'         =>          Request::get('client_id'),
                    'date'              =>          Request::get('date'),
                    'product_id'        =>          $temp[1],
                    'heads'             =>          $heads,  
                    'weight'            =>          $weight,
                ];

                \App\Donation_detail::create($donation_details);
            }
        }

        notify()->success('Donation Entry Created');
        return redirect('donations');
    }

    public function edit($id){
        $donation = \App\Donation::find($id);
        $products = Product::orderBy('position')->get();
        $details = \App\Donation_detail::where('donation_id',$donation->id)->get();
        return view('donations.edit',compact('donation','products','details'));
    }

    public function update($id){
        $donation = \App\Donation::find($id);
        foreach (Request::get('heads') as $key => $value){
            $donation_details = [
                'heads'             =>          $value,  
                'weight'            =>          Request::get('weight')[$key],  
                'unit_price'        =>          Request::get('unit_price')[$key],
                'total_price'       =>          Request::get('total_price')[$key],
            ];

            \App\Donation_detail::where('id',Request::get('id')[$key])->update($donation_details);  
        }

        notify()->success('Donation Entry Updated');
        return redirect()->back();
    }

    public function delete($id){
        $donation = \App\Donation::find($id);
        $donation->details()->delete();
        $donation->delete();

        notify()->success('Donation Entry Deleted');
        return redirect()->back();
    }
}
