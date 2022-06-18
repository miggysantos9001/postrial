<?php

namespace App\Http\Controllers;

use App\Setup;

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

class SetupController extends Controller
{
	public function __construct(){
	    $this->middleware('auth');
	}

	public function index(){
		$setups = Setup::all();
		return view('setups.index',compact('setups'));
	}

	public function store(){
		$validator = Validator::make(Request::all(), [
		    'name'						=>	'required',
		    'address'					=>	'required',
		    'mobile'					=>	'required',
		]);

		if ($validator->fails()) {
		    return redirect()->back()
                ->withErrors($validator)
                ->withInput();
		}

		$setup = Request::except('logo');
		$file = Request::file('logo');
        if($file !== NULL){
            $extension = $file->getClientOriginalExtension();
            $fileName = str_random(50).'.'.$extension;
            $file->move(public_path().'/logos',$fileName);
            $setup = array_add($setup,'logo',$fileName);
        }else{
            $setup = array_add($setup,'logo',NULL);
        }
        Setup::truncate();
        Setup::create($setup);

		notify()->success('Setup Entry Created');
    	return redirect()->back();
	}

	public function update($id){
		$setup = Setup::find($id);
		$validator = Validator::make(Request::all(), [
		    'name'						=>	'required',
		    'address'					=>	'required',
		    'mobile'					=>	'required',
		]);

		if ($validator->fails()) {
		    return redirect()->back()
                ->withErrors($validator)
                ->withInput();
		}

		$setupData = Request::except('logo');
		$file = Request::file('logo');
		
        if($file !== NULL){
            $extension = $file->getClientOriginalExtension();
            $fileName = str_random(50).'.'.$extension;
            $file->move(public_path().'/logos',$fileName);
            $setupData = array_add($setupData,'logo',$fileName);
        }else{
            $setupData = array_add($setupData,'logo',$setup->logo);
        }

        $setup->update($setupData);

		notify()->success('Setup Entry Updated');
    	return redirect()->back();
	}
}
