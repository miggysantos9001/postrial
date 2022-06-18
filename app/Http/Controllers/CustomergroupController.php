<?php

namespace App\Http\Controllers;

use App\Customergroup;

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


class CustomergroupController extends Controller
{
	public function __construct(){
	    $this->middleware('auth');
	}
	
    public function index(){
    	$customergroups = Customergroup::orderBy('name')->get();
    	return view('customergroups.index',compact('customergroups'));
    }

    public function store(){
		$validator = Validator::make(Request::all(), [
		    'name'						=>	'required|unique:customergroups',
		],
		[
		    'name.required'     		=>	'Customer Group Name Required',
		]);

		if ($validator->fails()) {
		    return redirect()->back()
                ->withErrors($validator)
                ->withInput();
		}

		Customergroup::create(Request::all());

		notify()->success('Customer Group Entry Created');
    	return redirect()->back();

    }

    public function update($id){
    	$customergroup = Customergroup::find($id);
		$validator = Validator::make(Request::all(), [
		    'name'						=>	"required|unique:customergroups,name,$customergroup->id,id",
		],
		[
		    'name.required'     		=>	'Customer Group Name Required',
		]);

		if ($validator->fails()) {
		    return redirect()->back()
                ->withErrors($validator)
                ->withInput();
		}

		$customergroup->update(Request::all());
		notify()->success('Customer Group Entry Updated');
    	return redirect()->back();
		
    }
}
