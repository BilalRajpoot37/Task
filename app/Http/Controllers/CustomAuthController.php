<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
class CustomAuthController extends Controller
{
    public function index()
    { 
        // return view for login

        return view('auth.login');
    }  
      
    public function customLogin(Request $request)
    {
        // validate the form data

        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

            // check if the user exists

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->intended('dashboard')
                        ->withSuccess('Signed in');
        }

        // if the user does not exist, redirect back to the login page
        return redirect("login")->withSuccess('Login details are not valid');
    }

    public function registration()
    {    // return view for registration

        return view('auth.registration');
    }
      
    public function customRegistration(Request $request)
    {  
        // validate the registration form data
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:customers',
            'password' => 'required|min:6',
        ]);
        
           // create a new user
        $data = $request->all();
        $check = $this->create($data);
         // if the user is created, redirect back to the login page
        return redirect("dashboard")->withSuccess('You have signed-in');
    }

    public function create(array $data)
    {
        // create a new user
      return customer::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password'])
      ]);
    }    
    
    public function dashboard()
    {
        // return dashboard view
        if(Auth::check()){
            return view('dashboard');
        }
  
        return redirect("login")->withSuccess('You are not allowed to access');
    }
    
    public function signOut() {
        // sign out the user
        Session::flush();
        Auth::logout();
  // redirect back to the login page
        return Redirect('login');
    }
}