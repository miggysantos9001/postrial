<?php

namespace App\Http\Controllers;

use App\Term;

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

class TermController extends Controller
{
	public function __construct(){
	    $this->middleware('auth');
	}
	
    public function index(){
    	$terms = Term::orderBy('name')->get();
    	return view('terms.index',compact('terms'));
    }

    public function store(){
		$validator = Validator::make(Request::all(), [
		    'name'						=>	'required|unique:terms',
		],
		[
		    'name.required'     		=>	'Term Name Required',
		]);

		if ($validator->fails()) {
		    return redirect()->back()
                ->withErrors($validator)
                ->withInput();
		}

		Term::create(Request::all());

		notify()->success('Term Entry Created');
    	return redirect()->back();

    }

    public function update($id){
    	$term = Term::find($id);
		$validator = Validator::make(Request::all(), [
		    'name'						=>	"required|unique:terms,name,$term->id,id",
		],
		[
		    'name.required'     		=>	'Term Name Required',
		]);

		if ($validator->fails()) {
		    return redirect()->back()
                ->withErrors($validator)
                ->withInput();
		}

		$term->update(Request::all());
		notify()->success('Term Entry Updated');
    	return redirect()->back();
		
    }
}
