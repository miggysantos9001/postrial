<?php

namespace App\Http\Controllers;

use App\Expensetype;

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


class ExpensetypeController extends Controller
{
	public function __construct(){
	    $this->middleware('auth');
	}
	
    public function index(){
    	$expensetypes = Expensetype::orderBy('name')->get();
    	return view('expensetypes.index',compact('expensetypes'));
    }

    public function store(){
		$validator = Validator::make(Request::all(), [
		    'name'						=>	'required|unique:expensetypes',
		],
		[
		    'name.required'     		=>	'Expense Type Name Required',
		]);

		if ($validator->fails()) {
		    return redirect()->back()
                ->withErrors($validator)
                ->withInput();
		}

		Expensetype::create(Request::all());

		notify()->success('Expense Type Entry Created');
    	return redirect()->back();

    }

    public function update($id){
    	$expensetype = Expensetype::find($id);
		$validator = Validator::make(Request::all(), [
		    'name'						=>	"required|unique:expensetypes,name,$expensetype->id,id",
		],
		[
		    'name.required'     		=>	'Expense Type Name Required',
		]);

		if ($validator->fails()) {
		    return redirect()->back()
                ->withErrors($validator)
                ->withInput();
		}

		$expensetype->update(Request::all());
		notify()->success('Expense Type Entry Updated');
    	return redirect()->back();
		
    }
}
