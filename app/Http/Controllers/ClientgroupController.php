<?php

namespace App\Http\Controllers;

use App\Clientgroup;
use App\Clientgroup_member;
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

class ClientgroupController extends Controller
{
    public function __construct(){
	    $this->middleware('auth');
	}

	public function index(){
		$clientgroups = Clientgroup::orderBy('name')->get();
		$clients = Client::orderBy('name')->get()->pluck('name','id');
		return view('clientgroups.index',compact('clientgroups','clients'));
	}

	public function store(){
		$validator = Validator::make(Request::all(), [
		    'name'						=>	'required|unique:clientgroups',
		    'client_id'					=>	'required|array|min:1',
		],
		[
		    'name.required'     		=>	'Client Group Name Required',
		    'client_id.required'		=>	'Please select clients',
		]);

		if ($validator->fails()) {
		    return redirect()->back()
                ->withErrors($validator)
                ->withInput();
		}

		$cg = Clientgroup::create(Request::except('client_id'))->id;

		foreach(Request::get('client_id') as $key => $value){
			$cgm = [
				'clientgroup_id'		=>		$cg,
				'client_id'				=>		$value,
			];

			Clientgroup_member::create($cgm);
		}

		notify()->success('Client Group Entry Created');
    	return redirect()->back();
	}

	public function update($id){
		$cgi = Clientgroup::find($id);
		$validator = Validator::make(Request::all(), [
		    'name'						=>	"required|unique:clientgroups,name,$cgi->id,id",
		    'client_id'					=>	'required|array|min:1',
		],
		[
		    'name.required'     		=>	'Client Group Name Required',
		    'client_id.required'		=>	'Please select clients',
		]);

		if ($validator->fails()) {
		    return redirect()->back()
                ->withErrors($validator)
                ->withInput();
		}

		$cgi->update(Request::except('client_id'));
		$cgi->members()->delete();

		foreach(Request::get('client_id') as $key => $value){
			$cgm = [
				'clientgroup_id'		=>		$cgi->id,
				'client_id'				=>		$value,
			];

			Clientgroup_member::create($cgm);
		}

		notify()->success('Client Group Entry Updated');
    	return redirect()->back();
	}
}
