<?php

namespace App\Http\Controllers;

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


class ClientController extends Controller
{
    public function __construct(){
	    $this->middleware('auth');
	}

	public function index(){
    	$clients = Client::orderBy('name')->get();
    	return view('clients.index',compact('clients'));
    }

    public function store(){
		$validator = Validator::make(Request::all(), [
		    'name'						=>	'required|unique:clients',
		],
		[
		    'name.required'     		=>	'Client Name Required',
		]);

		if ($validator->fails()) {
		    return redirect()->back()
                ->withErrors($validator)
                ->withInput();
		}

		Client::create(Request::all());

		notify()->success('Client Entry Created');
    	return redirect()->back();

    }

    public function update($id){
    	$client = Client::find($id);
		$validator = Validator::make(Request::all(), [
		    'name'						=>	"required|unique:clients,name,$client->id,id",
		],
		[
		    'name.required'     		=>	'Client Name Required',
		]);

		if ($validator->fails()) {
		    return redirect()->back()
                ->withErrors($validator)
                ->withInput();
		}

		$client->update(Request::all());
		notify()->success('Client Entry Updated');
    	return redirect()->back();
		
    }
}
