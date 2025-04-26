<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Order;
use App\Models\Wallet;
use App\Models\UserLogs;
use App\Models\Referrals;
use Illuminate\Http\Request;
use App\Models\VirtualAccount;
use App\Services\Monnify\VerifyBVNService;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon; // Import the Carbon library

class UserController extends Controller
{
    //
    public function index()
    {
        return view('user.user.profile');
    }

    public function verifyInformation(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'bvn' => 'required',
            'phone' => 'required',
            'dob' => 'required',
        ]);
        
        $response = (new VerifyBVNService())->informationMatch($data);

        return response()->json($response);
    }

    public function verifyAccount(Request $request)
    {

        $data = $request->validate([
            'bank_code' => 'required',
            'account_number' => 'required',
            'bvn' => 'required',
            'username' => 'required',
        ]);

        $response = (new VerifyBVNService())->accountMatch($data);

        if ($response && $response['status']) {
            // check if match is 100% before updating user record
            if ($response['data']['matchStatus'] === "FULL_MATCH" && $response['data']['matchPercentage'] === 100) {
                $user = \App\Models\User::where('username', $request->username)->first();

                if (!$user) {
                    return response()->json(['status' => 'failed', 'message' => 'User not found']);
                }

                // check if user is already verified
                if ($user->isVerified  == 1) {
                    return response()->json(['status' => 'failed', 'message' => 'User is already verified']);
                }

                // check if the BVN already exists in another record
                $existingBVN = \App\Models\User::where('bvn', $response['data']['bvn'])->first();
                if ($existingBVN) {
                    return response()->json(['status' => 'failed', 'message' => 'BVN is already associated with another account']);
                }

                // Update user details
                $user->update([
                    'bvn' => $response['data']['bvn'],
                    'accountName' => $response['data']['accountName'],
                    'verifiedName' => $response['data']['accountName'],
                    'bankCode' => $request->bank_code,
                    'isVerified' => 1,
                    'bvn_verified' => true
                ]);

                return response()->json(['status' => 'success', 'message' => 'User BVN verified and updated']);
            }

            return response()->json(['status' => 'failed', 'message' => 'Your BVN did not match the account number']);
        }

        return response()->json(['status' => 'failed', 'message' => 'Verification failed. Please try again later']);

    }


    public function dashboard()
    { 
        $orders = Order::whereNotNull('status')
                    ->where('userID', auth()->user()->id)
                    ->orderBy('id', 'desc')->count();
                    
        return view('user.dashboard', [
            'orders' => $orders,
        ]);
    }

    public function showPassword()
    {
        return view('user.user.password');
    }

    public function showPin()
    {
        return view('user.user.pin');
    }

    public function logs()
    {
        $logs = UserLogs::whereNotNull('status')->where('userID', auth()->user()->id)->orderBy('id', 'desc')->get();
        return view('user.user.logs', ["logs"=> $logs]);
    }


    public function updateProfile(Request $request)
    {
        // Validation logic here
        $credentials = $request->validate([
            'userId' => ['required'],
            'firstName' => ['required', 'min:4'],
            'lastName' => ['required', 'min:4'],
            'username' => 'required',
            'phone' => 'required|min:11|max:15',
            'email' => 'required|email',
            'dob' => 'required',
            'gender' => 'required',
            'nin' => 'required',
            'bvn' => 'required',
            'state' => 'required',
            'address' => 'required',
        ]);

        $user = User::find($request->userId);
        if ($user && $user->id == auth()->user()->id) {
            $user->update($credentials);
            return redirect()->route('user.profile')->with('message', 'Profile has been updated!');            
        } else {
            // return response()->json(['message' => 'Admin verification failed'], 401);
            return redirect()->back()->with('message', 'User Verification Failed');
        }
        return redirect()->back()->with('message', 'There is an error. Try again');
    }

    public function updatePassword(Request $request)
    {
        $credentials = $request->validate([
            'userId' => ['required'],
            'oldPassword' => 'required|min:8',
            'password' => 'required|confirmed|min:8',
        ]);
        $user = User::find($request->userId);
        // If user is verified, compare hashed passwords
        if ($user && Hash::check($request->input('oldPassword'), $user->password)) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
            return redirect()->route('user.show.password')->with('message', 'Password has been updated!');            
        } else {
            return redirect()->back()->with('message', 'User Verification Failed');
        }
        return redirect()->back()->with('message', 'There is an error. Try again');
    }

    public function updatePin(Request $request)
    {
        $credentials = $request->validate([
            'userId' => ['required'],
            'oldPassword' => 'required|min:8',
            'pin' => 'required|confirmed|min:4',
        ]);
        $user = User::find($request->userId);
        // If user is verified, compare hashed passwords
        if ($user && Hash::check($request->input('oldPassword'), $user->password)) {
            $user->update([
                'pin' => Hash::make($request->pin),
            ]);
            return redirect()->route('user.show.pin')->with('message', 'Pin has been updated!');            
        } else {
            return redirect()->back()->with('message', 'User Verification Failed');
        }
        return redirect()->back()->with('message', 'There is an error. Try again');
    }

    public function setPins(Request $request)
    {
        $credentials = $request->validate([
            'userID' => ['required'],
            'pin' => 'required|confirmed|min:4',
        ]);
        $user = User::find($request->userId);
        // If user is verified, compare hashed passwords
        if ($user) {
            $user->update([
                'pin' => Hash::make($request->pin),
            ]);
            return redirect()->route('user.verify')->with('message', 'Pin has been set!');            
        } else {
            return redirect()->back()->with('message', 'User Verification Failed');
        }
        return redirect()->back()->with('message', 'There is an error. Try again');
    }

    public function generateRandomCode()
    {
        $randomBytes = random_bytes(10); // Generates 10 random bytes
        $code = bin2hex($randomBytes); // Convert the bytes to hexadecimal representation
        return $code;
    }

    public function generateRandomNumbers()
    {
        $min = 100000; // Minimum 6-digit number
        $max = 999999; // Maximum 6-digit number
        // Generate a random number within the specified range
        $randomNumber = rand($min, $max);
        return $randomNumber;
    }


}

