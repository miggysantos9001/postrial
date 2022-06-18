<?php

namespace App\Http\Controllers;

use App\Product;

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

class ProductController extends Controller
{
	public function __construct(){
	    $this->middleware('auth');
	}
	
    public function index(){
    	$products = Product::orderBy('position')->get();
    	return view('products.index',compact('products'));
    }

    public function store(){
		$validator = Validator::make(Request::all(), [
		    'name'						=>	'required|unique:products',
		],
		[
		    'name.required'     		=>	'Product Name Required',
		]);

		if ($validator->fails()) {
		    return redirect()->back()
                ->withErrors($validator)
                ->withInput();
		}

		$checkLast = Product::orderBy('position','DESC')->first();
    	$pos = $checkLast->position + 1;

		$product = Request::except('isHead');
		if(Request::get('isHead') == 1){
			$isHead = 1;
		}else{
			$isHead = 0;
		}
		$product  = array_add($product,'position',$pos);
		$product = array_add($product,'isHead',$isHead);

		Product::create($product);

		notify()->success('Product Entry Created');
    	return redirect()->back();

    }

    public function update($id){
    	$product = Product::find($id);
    	
		$validator = Validator::make(Request::all(), [
		    'name'						=>	"required|unique:products,name,$product->id,id",
		],
		[
		    'name.required'     		=>	'Product Name Required',
		]);

		if ($validator->fails()) {
		    return redirect()->back()
                ->withErrors($validator)
                ->withInput();
		}

		$productData = Request::except('isHead');

		if(Request::get('isHead') == 1){
			$isHead = 1;
		}else{
			$isHead = 0;
		}

		$productData = array_add($productData,'isHead',$isHead);

		$product->update($productData);
		notify()->success('Product Entry Updated');
    	return redirect()->back();
		
    }
}
