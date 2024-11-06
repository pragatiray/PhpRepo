<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function index(){
        return view('admin.login');
    }
    public function authenticate(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if ($validator->passes()){

            if(Auth::guard('admin')->attempt(['email' => $request->email,'password' => $request->password])){

                if (Auth::guard('admin')->user()->role != "admin"){
                    Auth::guard('admin')->logout();
                    return redirect()->route('admin.login')->with('error','You are not authorized to access this.'); 
                }
                return redirect()->route('admin.dashboard')->with('You are logged in');
                
            } else{
                return redirect()->route('admin.login')->with('Either email or password is incorrect');
            }
        } else{
            return redirect()->route('admin.login')
                    ->withInput()
                    ->withErrors($validator);
        }
    }
    public function logout(){
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login')->with('sucess','You have logged out successfully');
    }

}
