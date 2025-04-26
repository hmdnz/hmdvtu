<?php

namespace App\Http\Controllers\admin;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminsController extends Controller
{
    //
    public function index()
    {
        $adminCount = Admin::whereNotNull('status')->get()->count();
        $admins = Admin::whereNotNull('status')->orderBy('id', 'desc')->get();
        // return view('admin.admins', compact('adminCount', 'admins'));
        // dd($adminCount);
        return view('admin.admins.all', [
            'adminCount' => $adminCount,
            'admins' => $admins,
        ]);
    }

    public function showAddAdmin()
    {
        return view('admin.admins.add');
    }

    public function showEditAdmin(Admin $admin)
    {
        return view('admin.admins.edit', [
            'admin' => $admin
        ]);
    }

    
    public function addAdmin(Request $request)
    {
        // Validation logic here (you can use Laravel validation)
        $credentials = $request->validate([
            'firstName' => ['required', 'min:4'],
            'lastName' => ['required', 'min:4'],
            'phone' => 'required|unique:admins|min:11|max:15',
            'email' => 'required|email|unique:admins',
            'role' => ['required', 'min:4'],
            'password' => 'required|confirmed|min:8',
        ]);
        $credentials['status'] = 'Active'; //signup stage
        $credentials['password'] = bcrypt($credentials['password']);
        // Create a new user
        $admin = Admin::create($credentials);
        // Redirect to the user dashboard
        return redirect()->route('admin.admins')->with('message', 'New admin has been added');
    }

    public function editAdmin(Request $request)
    {
        // Validation logic here (you can use Laravel validation)
        $credentials = $request->validate([
            'id' => 'required',
            'firstName' => ['required', 'min:4'],
            'lastName' => ['required', 'min:4'],
            'phone' => 'required',
            'email' => 'required|email',
            'role' => 'required',
            'status' => 'required',
        ]);
        
        $admin = Admin::find($request->id);
        
        // $credentials['status'] = 'Active'; //signup stage
        if($request->has('password') ){   
            if($request->password == $request->confirmed_password){
                $credentials['password'] = bcrypt($request['password']);
            }else{
                redirect()->back()->with('message', 'The passwords did not match');
            }
        }
        $credentials['password'] = $admin['password'];
        // // Create a new user
        $admin->update($credentials);
        
        // dd($admin);
        // Redirect to the admin dashboard
        return redirect()->route('admin.admins')->with('message', 'Admin has been updated');
    }

}
