<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Wallet;
use App\Models\UserLogs;
use App\Models\Referrals;
use App\Models\VirtualAccount;
use App\Notifications\SendOtpNotification;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Enum\ReferralStatus;
use App\Enum\TransactionStatus;
use Carbon\Carbon; // Import the Carbon library

class AuthController extends Controller
{

    //
    public function index()
    {}

    public function showLogin()
    {
        return view('user.auth.signin');
    }
    
    public function showRegistration()
    {
        return view('user.auth.signup');
    }

    public function showForgetPassword()
    {
        return view('user.auth.forgetPassword');
    }

    public function showResetPassword()
    {
        return view('user.auth.resetPassword');
    }
    
    public function showVerifyPage()
    {
        return view('user.auth.verify');
    }

    public function showSetPin()
    {
        // check if pin is set
        if (isset(auth()->user()->pin)) 
        {
            return redirect()->route('user.dashboard')->with("message", 'You already set a pin!');
        }
        return view('user.auth.pin');
    }


    public function login(Request $request)
    {
        
        // Validation logic here

        if (Auth::guard('web')->attempt($request->only('username', 'password'))) {
            // if(auth()->user()->isVerified == 1)
            // {
                // // check if pin is set
                // if (isset(auth()->user()->pin)) 
                if (!is_null(auth()->user()->pin))
                {
                    $userLog = UserLogs::create([
                        'userID' => auth()->user()->id,
                        'username' => $request->username,
                        'IPAddress' => $_SERVER['REMOTE_ADDR'],
                        'status' => 'Signed-In',
                    ]);
                    return redirect()->route('user.dashboard');
                }
                else
                {
                    return redirect()->route('user.showSetPin');
                }
            // }
            // else
            // {
            //     return redirect()->route('user.verify');
            // }
            
        }
            $userLog = UserLogs::create([ 
                'username' => $request->username,
                'IPAddress' => $_SERVER['REMOTE_ADDR'],
                'status' => 'Failed',
            ]);
        // Handle failed login
        return redirect()->back()->withInput()->withErrors(['username' => 'Invalid credentials']);
    }

    public function register(Request $request)
    {
        // Validation logic here (you can use Laravel validation)
        $credentials = $request->validate([
            'firstName' => ['required', 'min:4'],
            'lastName' => ['required', 'min:4'],
            'username' => 'required|unique:users|max:15',
            'phone' => 'required|unique:users|min:11|max:15',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
            'referralId' => 'nullable',
        ]);
        $credentials['isVerified'] = 1; //signup stage
        $credentials['status'] = 'Active';
        $credentials['password'] = bcrypt($credentials['password']);

        // Create a new user
        $user = User::create([
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'username' => $request->username,
            'phone' => $request->phone,
            'email' => $request->email,
            'isVerified' => 0,
            'status' => 'Active',
            'password' => bcrypt($request->password),
        ]);

        // create wallet
        $walletIdentifier = $this->generateRandomCode();
        $wallet = Wallet::create([
            'userID' => $user->id,
            'identifier' => $walletIdentifier,
            'mainBalance' => 0,
            'referralBalance' => 0,
            'status' => 'Active',
        ]);

        // create virtual accounts (not more than 3. they are Moneipoint, Sterlin and Wema)
        // $RVA = $this->createReservedAccount($walletIdentifier, $user->username, $user->email);
        // foreach ($RVA as $account) {
        //     $virtualAccount = VirtualAccount::create([
        //         'user_id' => $user->id,
        //         'wallet_id' => $wallet->id,
        //         'gateway' => 'Monnify',
        //         'accountName' => $account->accountName,
        //         'accountNumber' => $account->accountNumber,
        //         'bankName' => $account->bankName,
        //         'status' => 'Active',
        //     ]); 
        // }
        
        // Log in the user
        Auth::login($user);
        $userLog = UserLogs::create([ 
            'userID' => auth()->user()->id,
            'username' => $request->username,
            'IPAddress' => $_SERVER['REMOTE_ADDR'],
            'status' => 'Signed-In',
        ]);

        // if referralid is provided, it will save it 
        if(isset($request->referralId))
        {
            // check if the referralId exist
            $referrer = User::where('username', $request->referralId)->first();
            if (!$referrer) {
                return redirect()->route('user.verify')->with('message', 'The referral code not found');
            }

            $referral = Referrals::create([
                'userID' => $user->id,
                'referrer' => $referrer->id,
                'commission' => 20,
                'status' => TransactionStatus::PENDING,
            ]);

            $user->update([
                'referralID' => $referral->id
            ]);
        }

        // // Redirect to the user dashboard
        return redirect()->route('user.verify')->with('message', 'You Sign-In successfully.');
    }

    public function forgetPassword(Request $request)
    {
        // Validation logic here

        // Handle failed login
        return redirect('/user/reset-password')->with('message', 'OTP has been sent');
    }

    public function resetPassword(Request $request)
    {
        // Validation logic here

        // Handle failed login
        return redirect()->route('user.login')->with('message', 'Password has been changed');
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
            return redirect()->route('user.profile')->with('message', 'Password has been updated!');            
        } else {
            // return response()->json(['message' => 'Admin verification failed'], 401);
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
            return redirect()->route('user.profile')->with('message', 'Pin has been updated!');            
        } else {
            return redirect()->back()->with('message', 'User Verification Failed');
        }
        return redirect()->back()->with('message', 'There is an error. Try again');
    }

    public function setPins(Request $request)
    {
        $credentials = $request->validate([
            'userId' => ['required'],
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

    public function setPin(Request $request)
    {
        $credentials = $request->validate([
            'pin' => 'required|confirmed|min:4',
        ]);

        $user = User::find(auth()->user()->id);
        if ($user) {
            $user->update([
                'pin' => Hash::make($request->pin),
            ]);
            return response()->json(['status' => 'success', 'message' => 'Pin has been set!']);     
        } else {
            return response()->json(['status' => 'failed', 'message' => 'Cannot get a user']);  
        }
        return response()->json(['status' => 'failed', 'message' => 'Failed to set a pin']);  
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

    public function logout()
    {
        $userLog = UserLogs::create([ 
            'userID' => auth()->user()->id,
            'username' =>  auth()->user()->username,
            'IPAddress' => $_SERVER['REMOTE_ADDR'],
            'status' => 'Signed-Out',
        ]);
        Auth::logout();
        return redirect()->route('user.login'); // Redirect to the login page after logout
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

    public function sendVerificationCode(Request $request)
    {
        $code = $this->generateRandomNumbers();
        // Save the verification code to the user model
        $user = User::find($request->userId);
        $user->token = $code;
        $user->generated_at = Carbon::now();
        if($user->save())
        {
            $user->notify(new SendOtpNotification($code));
            // $sendToPhone = $this->sendOTPToPhone($request->phoneContact, $code); 
            $sendToEmail = $this->sendOTPToEmail($request->emailContact, $code, auth()->user()->firstName); 
            // if ($sendToPhone || $sendToEmail) {
            if ($sendToEmail) {
                return response()->json(['success' => true, 'message' => 'OTP has been sent']);
            }else{
                return response()->json(['errors' => 'Try Again later'], 422);
            }
            // return response()->json(['success' => true, 'message' => 'OTP has been sent']);
            return response()->json(['success' => false, 'message' => 'Sorry! There is a problem']);
            
        }
        
        return response()->json(['success' => true, 'message' => 'OTP has been sent']);
        
        // Assuming the OTP was sent successfully

    }

    
    public function sendOTPToEmail($email, $code, $rName)
    {
        $mail = new PHPMailer(true); // true enables exceptions

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.titan.email'; // SMTP server
            $mail->SMTPAuth   = true;
            $mail->Username   = 'info@zaumadata.com.ng';
            $mail->Password   = 'Email@info1';
            $mail->SMTPSecure = 'tls'; // Enable TLS encryption
            $mail->Port       = 465;

            // Sender and recipient settings
            $mail->setFrom('info@zaumadata.com.ng', 'Zauma Data');
            $mail->addAddress($email, $rName);

            // Email content
            $mail->isHTML(true);
            $mail->Subject = 'Verify your Account';
            $mail->Body    = '<p>Hello, this is your OTP to verify your account : </p>'. $code;

            $mail->send();
            return "Email sent successfully!";
        } catch (Exception $e) {
            return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    public function sendOTPToPhone($phone, $code)
    {
        $message = "Your One-Time Passowrd for verifying your account is" . $code;
        $headers = array('Content-Type: application/x-www-form-urlencoded');
        $url = 'https://www.bulksmsnigeria.com/api/v1/sms/create';
        $arr_params = [
            'from'      =>  'AARasheedDa',
            'to'          =>  $phone,
            'body'      =>  $message,
            'append_sender' => 3,
            'api_token' =>  'LFKP1OtTZxU6HZdvBWL6obBfWFNf2QyUOm8awvsJv2d9c5eQhIXioKv5KG3Z', //Todo: Replace with your API Token
            'dnd'       =>  4
        ];
        if (is_array($arr_params)) {
            $final_url_data = http_build_query($arr_params, '', '&');
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $final_url_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $response = curl_exec($ch);
        curl_close($ch);
        // echo $response;
        $obj = json_decode($response);
        if ($obj->data->status == 'success') {
            return true;
        } else {
            return false;
        }
    }

    public function verifyUser(Request $request)
    {
        // dd($request);
        $request->validate([
            'userId' => 'required|exists:users,id',
            'verification_code' => 'required|string',
        ]);
        // Get the user by ID
        $user = User::find($request->userId);
        // Check if the user exists
        if (!$user) {
            return redirect()->back()->with('message', 'User not found');
        }
        // Check if the verification code matches
        if ($user->token !== $request->verification_code) {
            return redirect()->back()->with('message', 'Invalid verification code');
        }
        // Calculate the expiration time (5 minutes after generated_at)
        $expirationTime = Carbon::parse($user->generated_at)->addMinutes(25);
        // Check if the verification code is expired
        if (Carbon::now()->gt($expirationTime)) {
            return redirect()->back()->with('message', 'Verification code has expired');
        }else{
         // Update the user's verified_at timestamp
         $user->update(['verified_at' => now()]);
         $user->update(['isVerified' => 2]);
        }
        return redirect('/user/verify')->with('message', 'Verification Successful');
  
    }
    // verify identity
    public function verifyIdentity(Request $request)
    {
        $request->validate([
            'userId' => 'required|exists:users,id',
            'identity_type' => 'required|string',
            'nin' => 'required|unique:users|max:15',
        ]);

        // Get the user by ID
        $user = User::find($request->userId);
        // Check if the user exists
        if (!$user) {
            return redirect()->back()->with('message', 'User not found');
        }
        // verify id
        if($request->identity_type == 'NIN')
        {
            // $status = $this->verifyNIN($request->identity_number);
            // dd($status);
            // Update the user's verified_at timestamp
         $user->update(['nin' => $request->identity_number]);
         $user->update(['isVerified' => 3]);
        } 
        else
        {
            // $status = $this->verifyBVN($request->identity_number);
            // Update the user's verified_at timestamp
         $user->update(['bvn' => $request->identity_number]);
         $user->update(['isVerified' => 3]);
        } 
        return redirect('/user/verify')->with('message', 'Verification Successful');
    }

    private function getAuthorization()
    {
        $key = base64_encode('MK_PROD_K1XRNL3CWV:TV2JAG0RZQMP4CDNLEX26RFRM3F11D71');
        // echo $key;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.monnify.com/api/v1/auth/login");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: Basic $key",
            'Content-type' => 'application/json',
        ));
        // $EPIN_response = curl_exec($ch);
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        // echo $EPIN_response;
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $obj = json_decode($response);
            // echo $response;
            if ($obj->requestSuccessful) {
                if ($obj->responseMessage == 'success') {
                    // $amount = ($obj->data->amount - $obj->data->fees) / 100;
                    return $obj->responseBody->accessToken;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

    public function createReservedAccount($walletIdentifier, $userName, $userEmail)
    {
        $token = $this->getAuthorization();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.monnify.com/api/v2/bank-transfer/reserved-accounts");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array(
            'accountReference' => $walletIdentifier,
            'accountName' => $userName,
            'currencyCode' => 'NGN',
            'contractCode' => '854262695245',
            'customerEmail' => $userEmail,
            'customerName' => $userName,
            'getAllAvailableBanks' => true,
        )));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer " . $token,
            "Cache-Control: no-cache",
            "Content-Type: application/json",
        ));
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        // echo $response;
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $obj = json_decode($response);
            if ($obj->requestSuccessful) {
                if ($obj->responseMessage == 'success') {
                    return $obj->responseBody->accounts;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

    // public function verifyNIN($nin)
    // {
    //     $token = $this->getAuthorization();
    //     // dd($nin);
    //      // 
    //      $curl = curl_init();
    //      curl_setopt_array($curl, array(
    //          CURLOPT_URL => "https://api.monnify.com/api/v1/vas/nin-details",
    //          CURLOPT_RETURNTRANSFER => true,
    //          CURLOPT_ENCODING => "",
    //          CURLOPT_MAXREDIRS => 10,
    //          CURLOPT_TIMEOUT => 30,
    //          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //          CURLOPT_CUSTOMREQUEST => "POST",
    //          CURLOPT_POSTFIELDS => array(
    //             "nin" => $nin
    //         ),
    //          CURLOPT_HTTPHEADER => array(
    //              "Authorization: Bearer " . $token,
    //              "Cache-Control: no-cache",
    //          ),
    //      ));
    //      $response = curl_exec($curl);
    //      $err = curl_error($curl);
    //      curl_close($curl);
    //      echo $response;
    //      if ($err) {
    //          echo "cURL Error #:" . $err;
    //      } else {
    //          $obj = json_decode($response);
    //          if ($obj->requestSuccessful) {
    //              if ($obj->responseMessage == 'success') {
    //                  return $obj->responseBody;
    //              } else {
    //                  return false;
    //              }
    //          } else {
    //              return false;
    //          }
    //      }
    // }
}

