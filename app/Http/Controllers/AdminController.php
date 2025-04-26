<?php

namespace App\Http\Controllers;

use App\Enum\GeneralStatus;
use App\Enum\UserStatus;
use App\Models\User;
use App\Models\Admin;
use App\Models\Order;
use App\Models\Wallet;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Service;
use App\Models\AdminLogs;
use App\Models\Transaction;
use App\Models\Announcement;
use App\Models\Biller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\CategorySwitch;
use App\Models\Provider;
use App\Models\Switches;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

    public function showLoginForm()
    {
        return view('admin.signin');
    }

    public function login(Request $request)
    {
        // Validation logic here
        if (Auth::guard('admin')->attempt($request->only('email', 'password'))) {
            if(auth('admin')->user()->status == UserStatus::ACTIVE)
            {
                $adminLog = AdminLogs::create([
                    'adminID' => auth('admin')->user()->id,
                    'username' => $request->username,
                    'IPAddress' => $_SERVER['REMOTE_ADDR'],
                    'status' => 'Signed-In',
                ]);
                return redirect()->route('admin.dashboard');
            }

            $adminLog = AdminLogs::create([
                'username' => $request->username,
                'IPAddress' => $_SERVER['REMOTE_ADDR'],
                'status' => 'Failed',
            ]);

            return redirect()->back()->with('message', 'Account is not active');
        }
        // Handle failed login
        return redirect()->back()->withInput()->withErrors(['email' => 'Invalid credentials']);
    }

    public function redirectTo()
    {
        if(Auth::guard('admin')->check())
        {
            return '/admin/dashboard';
        }

        return '/admin/signin';
    }

    public function showRegistrationForm()
    {
        return view('admin.register');
    }

    public function register(Request $request)
    {
        // Create a new user
        $admin = Admin::create([
            'firstName' => $request->input('firstName'),
            'lastName' => $request->input('lastName'),
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'role' => 'super-admin',
            'password' => bcrypt($request->input('password')),
        ]);

        // Log in the user
        Auth::login($admin);
        $adminLog = AdminLogs::create([ 
            'adminID' => auth('admin')->user()->id,
            'username' => $request->email,
            'IPAddress' => $_SERVER['REMOTE_ADDR'],
            'status' => 'Signed-In',
        ]);
        // Redirect to the user dashboard
        return redirect()->route('admin.dashboard');
    }

    public function dashboard()
    {
        $adminsCount = Admin::whereNotNull('status')->get()->count();
        $usersCount = User::whereNotNull('status')->get()->count();
        $packagesCount = Package::whereNotNull('status')->get()->count();
        $servicesCount = Service::whereNotNull('status')->get()->count();
        $ordersCount = Order::whereNotNull('status')->get()->count();
        $paymentsCount = Payment::whereNotNull('status')->get()->count();
        $transactionsCount = Transaction::whereNotNull('status')->get()->count();
        $walletsCount = Wallet::whereNotNull('status')->get()->count();
        // return view('admin.dashboard');
        return view('admin.dashboard', compact('adminsCount', 'usersCount', 'packagesCount', 'servicesCount', 'ordersCount', 'paymentsCount', 'transactionsCount', 'walletsCount'));
    }

    public function showAdminLogs(Admin $admin)
    {
        return view('admin.register');
    }

    public function logout()
    {
        
        $adminLog = AdminLogs::create([ 
            'adminID' => auth('admin')->user()->id,
            'username' => auth()->user()->email,
            'IPAddress' => $_SERVER['REMOTE_ADDR'],
            'status' => 'Signed-Out',
        ]);
        Auth::logout();
        return redirect()->route('admin.signin'); // Redirect to the login page after logout
    }

    
    public function showUsers()
    {
        return view('admin.users');
    }

    public function showCategories()
    {
        $categories = Category::whereNotNull('status')->orderBy('id', 'asc')->get();
        return view('admin.others.categories', [
            'categories' => $categories,
        ]);
    }

    public function showSwitches()
    {
        $billers = Biller::where('status', 'Active')->orderBy('id', 'asc')->get();
        $services = Service::where('status', 'Active')->orderBy('id', 'asc')->get();
        $switches = Switches::with(['provider', 'biller', 'service'])->whereNotNull('status')->orderBy('id', 'desc')->get();
        $providers = Provider::whereNotNull('status')->orderBy('id', 'asc')->get();
        return view('admin.others.switches', [
            'switches' => $switches,
            'billers' => $billers,
            'services' => $services,
            'providers' => $providers,
        ]);
    }

    public function storeSwitches(Request $request)
    {
        $data = $request->validate([
            'context_type' => 'required|in:category,biller,service',
            'service_provider_id' => 'required|exists:providers,id',
            'category_title' => 'nullable|string',
            'context_id_biller' => 'nullable|exists:billers,id',
            'context_id_service' => 'required|exists:services,id',
            'context_id_biller_category' => 'required_if:context_type,category',
        ]);
    
        $contextId = null;
        $categoryTitle = null;
    
        if ($data['context_type'] === 'biller') {
            $contextId = $data['context_id_biller'];
        } elseif ($data['context_type'] === 'service') {
            $contextId = $data['context_id_service'];
        } elseif ($data['context_type'] === 'category') {
            $contextId = $data['context_id_biller_category'];
            $categoryTitle = $data['category_title'];
        }
    
        Switches::create([
            'context_type' => $data['context_type'],
            'context_id' => $contextId,
            'category_title' => $categoryTitle,
            'provider_id' => $data['service_provider_id'],
            'service_id' => $data['context_id_service'],
            'status' => GeneralStatus::ACTIVE,
        ]);
    
        return redirect()->back()->with('success', 'Default service provider saved!');
    }

    public function updateSwitches(Request $request, $id)
    {
        $request->validate([
            'service_provider_id' => 'required|exists:providers,id',
        ]);

        $entry = Switches::findOrFail($id);
        $entry->update([
            'provider_id' => $request->service_provider_id,
        ]);

        return redirect()->back()->with('success', 'Default provider updated.');
    }

    public function deleteSwitches(Request $request, $id)
    {
        $entry = Switches::findOrFail($id);
        $entry->update([
            'status' => null,
        ]);

        return redirect()->back()->with('success', 'Switch has been deleted.');
    }

    public function showAnnouncements()
    {
        $announcements = Announcement::whereNotNull('status')->orderBy('id', 'asc')->get();
        return view('admin.others.announcements', [
            'announcements' => $announcements,
        ]);
    }

    public function showAnnouncement(Announcement $announcement)
    {
        return view('admin.others.showAnnouncement', [
            'announcement' => $announcement,
        ]);
    }

    public function editAnnouncement(Announcement $announcement, Request $request)
    {
        // echo $request->id;
        $credentials = $request->validate([
            'title' => 'required',
            'body' => 'required',
        ]);

        $announcement->update($credentials);
        return redirect()->back()->with('message', 'You successfully updated the announcement');
    }

    public function addAnnouncement(Request $request)
    {
        // echo $request->id;
        $credentials = $request->validate([
            'title' => 'required',
            'body' => 'required',
        ]);

        $data = Announcement::create([
            "adminID" => auth('admin')->user()->id,
            "title" => $request->title,
            "body" => $request->body,
            "status" => 'Active',
        ]);

        if($data)
        {
            return redirect()->back()->with('message', 'You successfully added an announcement');
        }
        
        return redirect()->back()->with('message', 'There is an error.Try again');

    }

    public function updateCategory(Request $request)
    {
        // echo $request->id;
        $credentials = $request->validate([
            'id' => 'required',
            'biller' => 'required',
            'status' => 'required',
        ]);

        $switch = Category::find($request->id); // Assuming you have the $userId variable
        if ($switch) {
            // Update the switch's record in the switchs table
            $switch->update([
                $request->biller => $request->status,
            ]);
            return response()->json(['success' => true, 'message' => 'Switch has been updated!']);
            // return redirect()->route('admin.switches')->with('message', 'Service Switch has been updated!');
        }else{
            // return redirect()->back()->with('message', 'Switch not found');
            return response()->json(['success' => false, 'message' => 'Switch not found']);
        }
        // return redirect()->back()->with('message', 'There is a problem. Try again later');
            return response()->json(['success' => false, 'message' => 'There is a problem. Try again later!']);
    }

}
