<?php

namespace App\Http\Controllers;

use App\Expensetype;
use App\Expense;

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

class ExpenseController extends Controller
{
	public function __construct(){
	    $this->middleware('auth');
	}
	
    public function index(){
    	$expenses = Expense::orderBy('date','DESC')->get();
    	$expensetypes = Expensetype::pluck('name','id');
    	return view('expenses.index',compact('expenses','expensetypes'));
    }

    public function store(){
		$validator = Validator::make(Request::all(), [
		    'date'						=>	'required|date',
		    'expensetype_id'			=>	'required',
		    'amount'					=>	'required',
		],
		[
		    'expensetype_id.required'   =>	'Please select expense type',
		]);

		if ($validator->fails()) {
		    return redirect()->back()
                ->withErrors($validator)
                ->withInput();
		}

		Expense::create(Request::all());

		notify()->success('Expense Entry Created');
    	return redirect()->back();

    }

    public function update($id){
    	$expense = Expense::find($id);
		$validator = Validator::make(Request::all(), [
		    'date'						=>	'required|date',
		    'expensetype_id'			=>	'required',
		    'amount'					=>	'required',
		],
		[
		    'expensetype_id.required'   =>	'Please select expense type',
		]);

		if ($validator->fails()) {
		    return redirect()->back()
                ->withErrors($validator)
                ->withInput();
		}

		$expense->update(Request::all());
		notify()->success('Expense Entry Updated');
    	return redirect()->back();
		
    }

    public function delete($id){
    	$expense = Expense::find($id);
    	$expense->delete();

    	notify()->success('Expense Entry Deleted');
    	return redirect()->back();
    }
}
