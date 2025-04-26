<?php

namespace App\Http\Controllers\admin;

use App\Models\Admin;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Provider;
use Illuminate\Support\Facades\Hash;

class ServicesController extends Controller
{
    //

    public function index()
    {
        $serviceCount = Service::whereNotNull('status')->get()->count();
        $services = Service::whereNotNull('status')->orderBy('id', 'desc')->get();
        $providers = Provider::whereNotNull('status')->orderBy('id', 'desc')->get();
        return view('admin.services.all', [
            'serviceCount' => $serviceCount,
            'services' => $services,
            'providers' => $providers,
        ]);
    }
    
    public function addService(Request $request)
    {
        // Validation logic here (you can use Laravel validation)
        $credentials = $request->validate([
            'title' => 'required',
            'providerID' => 'required',
            'status' => 'required',
        ]);
        $credentials['adminID'] = auth()->user()->id; 
        // Create a new service
        $service = Service::create($credentials);
        // Redirect to the user dashboard
        return redirect()->route('admin.services')->with('message', 'New service has been added');
    }

    public function editService(Request $request)
    {
        $credentials = $request->validate([
            'title' => 'required',
            'providerID' => 'required',
            'id' => 'required',
        ]);

        $service = Service::find($request->id); // Assuming you have the $userId variable
        if ($service) {
            // Update the service's record in the services table
            $service->update([
                'title' => $request->title,
                'providerID' => $request->providerID,
            ]);
            return redirect()->route('admin.services')->with('message', 'service has been updated!');
        }else{
            return redirect()->back()->with('message', 'Service not found');
        }
        return redirect()->back()->with('message', 'There is a problem. Try again later');
    }

    public function activate(Service $service)
    {
        $service = Service::find($service->id); // Assuming you have the $serviceId variable
        if ($service) {
            // Update the service's record in the services table
            $service->update([
                'status' => 'Active',
            ]);
            return redirect()->route('admin.services')->with('message', 'service has been updated!');
            // return response()->json(['message' => 'service record updated successfully'], 200);
        } else {
            return redirect()->back()->with('message', 'service not found');
            // return response()->json(['message' => 'service not found'], 404);
        }
        return redirect()->back()->with('message', 'There is an error. Try again');
    }

    public function deactivate(Service $service)
    {
        $service = Service::find($service->id); // Assuming you have the $serviceId variable
        if ($service) {
            // Update the service's record in the services table
            $service->update([
                'status' => 'Inactive',
            ]);
            return redirect()->route('admin.services')->with('message', 'service has been updated!');
            // return response()->json(['message' => 'service record updated successfully'], 200);
        } else {
            return redirect()->back()->with('message', 'service not found');
            // return response()->json(['message' => 'service not found'], 404);
        }
        return redirect()->back()->with('message', 'There is an error. Try again');
    }

    public function delete(Request $request)
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
            $service = Service::find($request->id); // Assuming you have the $serviceId variable
            if ($service) {
                // Update the service's record in the services table
                $service->update([
                    'status' => null,
                ]);
                return redirect()->route('admin.services')->with('message', 'service has been deleted!');
                // return response()->json(['message' => 'service record updated successfully'], 200);
            } else {
                return redirect()->back()->with('message', 'service not found');
                // return response()->json(['message' => 'service not found'], 404);
            }
        } else {
            // return response()->json(['message' => 'Admin verification failed'], 401);
            return redirect()->back()->with('message', 'Admin Verification Failed');
        }
        
        return redirect()->back()->with('message', 'There is an error. Try again');
    }
}
