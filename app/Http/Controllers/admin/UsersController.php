<?php

namespace App\Http\Controllers\admin;

use Wallet;
use Carbon\Carbon;
use VirtualAccount;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserLogs;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    //
       //
       public function index()
       {
           $userCount = User::whereNotNull('status')->get()->count();
           $users = User::whereNotNull('status')->orderBy('id', 'desc')->get();
           return view('admin.users.all', [
               'userCount' => $userCount,
               'users' => $users,
           ]);
       }
   
       public function showUser(User $user)
       {
        if ($user->status){
            return view('admin.users.view', [
                'user' => $user
            ]);
        }else{
            return redirect()->route('admin.users')->with('message', 'User not found');
        }
           // // Fetch details from wallet
           // $walletDetails = Wallet::where('user_id', $userId)->first();
   
           // // Fetch details from virtual accounts
           // $virtualAccountDetails = VirtualAccount::where('user_id', $userId)->first();
   
           // // Combine the information into one object
           // $userDetails = [
           //     'user' => $user,
           //     'wallet' => $walletDetails,
           //     'virtual_account' => $virtualAccountDetails,
           // ];
       }

       public function verify(Request $request, User $user)
       {
        $credentials = $request->validate([
            'adminId' => ['required'],
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $admin = Admin::where([
            'id' => $request->adminId,
            'email' => $request->email,
        ])->first();
        
        // If admin is verified, compare hashed passwords
        if ($admin && Hash::check($request->input('password'), $admin->password)) {
            $user = User::find($user->id); // Assuming you have the $userId variable
            if ($user) {
                // Update the user's record in the users table
                $user->update([
                    'isVerified' => 4,
                    'admin_verified_at' => Carbon::now(),
                    'admin_verified_by' => $admin->id,
                ]);
                return redirect()->route('admin.users.view', [$user->id])->with('message', 'User has been verified successfully!');
                // return response()->json(['message' => 'User record updated successfully'], 200);
            } else {
                return redirect()->back()->with('message', 'User not found');
                // return response()->json(['message' => 'User not found'], 404);
            }
        } else {
            // return response()->json(['message' => 'Admin verification failed'], 401);
            return redirect()->back()->with('message', 'Admin Verification Failed');
        }
        
        return redirect()->back()->with('message', 'There is error. Try again');
       }

       public function resetPassword(Request $request, User $user)
       {
        $credentials = $request->validate([
            'adminId' => ['required'],
            'email' => 'required|email',
            'adminPassword' => 'required|min:8',
            'password' => 'required|confirmed|min:8',
        ]);

        $admin = Admin::where([
            'id' => $request->adminId,
            'email' => $request->email,
        ])->first();
        
        // If admin is verified, compare hashed passwords
        if ($admin && Hash::check($request->input('adminPassword'), $admin->password)) {
            $user = User::find($user->id); // Assuming you have the $userId variable
            if ($user) {
                // Update the user's record in the users table
                $credentials['password'] = bcrypt($credentials['password']);
                $user->update([
                    'password' => bcrypt($request->password),
                ]);
                return redirect()->route('admin.users.view', [$user->id])->with('message', 'User password has been updated!');
                // return response()->json(['message' => 'User record updated successfully'], 200);
            } else {
                return redirect()->back()->with('message', 'User not found');
                // return response()->json(['message' => 'User not found'], 404);
            }
        } else {
            // return response()->json(['message' => 'Admin verification failed'], 401);
            return redirect()->back()->with('message', 'Admin Verification Failed');
        }
        
        return redirect()->back()->with('message', 'There is error. Try again');
       }

       public function resetPin(Request $request, User $user)
       {
        $credentials = $request->validate([
            'adminId' => ['required'],
            'email' => 'required|email',
            'adminPassword' => 'required|min:8',
            'pin' => 'required|confirmed|min:4',
        ]);

        $admin = Admin::where([
            'id' => $request->adminId,
            'email' => $request->email,
        ])->first();
        
        // If admin is verified, compare hashed passwords
        if ($admin && Hash::check($request->input('adminPassword'), $admin->password)) {
            $user = User::find($user->id); // Assuming you have the $userId variable
            if ($user) {
                // Update the user's record in the users table
                $user->update([
                    'pin' => bcrypt($request->pin),
                ]);
                return redirect()->route('admin.users.view', [$user->id])->with('message', 'User pin has been updated!');
                // return response()->json(['message' => 'User record updated successfully'], 200);
            } else {
                return redirect()->back()->with('message', 'User not found');
                // return response()->json(['message' => 'User not found'], 404);
            }
        } else {
            // return response()->json(['message' => 'Admin verification failed'], 401);
            return redirect()->back()->with('message', 'Admin Verification Failed');
        }
        
        return redirect()->back()->with('message', 'There is an error. Try again');
       }

       public function delete(Request $request, User $user)
       {
        $credentials = $request->validate([
            'adminId' => ['required'],
            'email' => 'required|email',
            'adminPassword' => 'required|min:8',
        ]);

        $admin = Admin::where([
            'id' => $request->adminId,
            'email' => $request->email,
        ])->first();
        
        // If admin is verified, compare hashed passwords
        if ($admin && Hash::check($request->input('adminPassword'), $admin->password)) {
            $user = User::find($user->id); // Assuming you have the $userId variable
            if ($user) {
                // Update the user's record in the users table
                $user->update([
                    'status' => null,
                ]);
                return redirect()->route('admin.users.view', [$user->id])->with('message', 'User has been deleted!');
                // return response()->json(['message' => 'User record updated successfully'], 200);
            } else {
                return redirect()->back()->with('message', 'User not found');
                // return response()->json(['message' => 'User not found'], 404);
            }
        } else {
            // return response()->json(['message' => 'Admin verification failed'], 401);
            return redirect()->back()->with('message', 'Admin Verification Failed');
        }
        
        return redirect()->back()->with('message', 'There is an error. Try again');
       }

       public function userLogs(User $user)
       {
        $userLogs = UserLogs::where('username', $user->username)->get();
            return view('admin.users.logs', [
                'user' => $user,
                'logs' => $userLogs
            ]);
        }
}
