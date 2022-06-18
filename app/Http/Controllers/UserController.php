<?php

namespace App\Http\Controllers;

use App\User;

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

class UserController extends Controller
{
	public function __construct(){
	    $this->middleware('auth');
	}

	public function index(){
		$users = User::orderBy('name')->get();
    	return view('users.index',compact('users'));
	}

	public function store(){
		$validator = Validator::make(Request::all(), [
		    'name'						=>	'required|unique:users',
		],
		[
		    'name.required'     		=>	'Username Required',
		]);

		if ($validator->fails()) {
		    return redirect()->back()
                ->withErrors($validator)
                ->withInput();
		}

		$user = new User;
		$user['name'] = preg_replace('/\s+/', '',strtolower(Request::get('name')));
		$user['password'] = Hash::make(preg_replace('/\s+/', '',strtolower(Request::get('name'))));
		$user->save();

		notify()->success('User Created');
    	return redirect()->back();
	}

	public function update($id){
    	$user = User::find($id);
		$validator = Validator::make(Request::all(), [
		    'name'						=>	"required|unique:users,name,$user->id,id",
		],
		[
		    'name.required'     		=>	'Username Required',
		]);

		if ($validator->fails()) {
		    return redirect()->back()
                ->withErrors($validator)
                ->withInput();
		}

		$user->update(Request::all());
		notify()->success('User Entry Updated');
    	return redirect()->back();
	}

	public function changepassword($id){
		$user = User::find($id);
		return view('changepass',compact('user'));
	}

	public function update_changepassword($id){
		$user = User::find($id);
		
		$validator = Validator::make(Request::all(), [
		    'oldpassword'				=>	"required",
		    'newpassword'				=>	"required|min:6",
		    'confirmpassword'			=>	"required|min:6",
		],
		[
		    'oldpassword.required'		=>	'Old Password Required',
		    'newpassword.required'		=>	'New Password Required',
		    'confirmpassword.required'	=>	'Confirm Password Required',
		]);

		if ($validator->fails()) {
		    return redirect()->back()
                ->withErrors($validator)
                ->withInput();
		}

		$oldpass = Request::get('oldpassword');
		$newpass = Request::get('newpassword');
		$conpass = Request::get('confirmpassword');

		if(Hash::check($oldpass,$user->password)){
			if($newpass == $conpass){
				$user->update([
					'password'	=>	Hash::make($conpass),
				]);
				notify()->success('Password Changed Successfully');
			}else{
				notify()->error('New and Confirm Password is Incorrect');
			}
		}else{
			notify()->error('Old Password Incorrect');
		}

		return redirect()->back();
	}
}
