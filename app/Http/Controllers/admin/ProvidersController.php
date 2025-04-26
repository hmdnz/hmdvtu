<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Provider;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProvidersController extends Controller
{
    //
    
    public function index()
    {
        $providerCount = Provider::whereNotNull('status')->get()->count();
        $providers = Provider::whereNotNull('status')->orderBy('id', 'desc')->get();
        $services = Service::whereNotNull('status')->orderBy('id', 'desc')->get();
        return view('admin.providers.all', [
            'providerCount' => $providerCount,
            'providers' => $providers,
            'services' => $services
        ]);
    }
    
    public function addProvider(Request $request)
    {
        // Validation logic here (you can use Laravel validation)
        $credentials = $request->validate([
            'title' => 'required',
            'key' => 'required',
            'service' => 'required',
            'status' => 'nullable',
        ]);

        $credentials['adminID'] = auth()->user()->id; 
        // Create a new service
        $provider = Provider::create($credentials);
        // Redirect to the user dashboard
        return redirect()->route('admin.providers')->with('message', 'New provider has been added');
    }

    public function editProvider(Request $request)
    {
        $credentials = $request->validate([
            'id' => 'required',
            'title' => 'required',
            'key' => 'required',
            'service' => 'required',
        ]);

        $provider = Provider::find($request->id);
        if ($provider) {
        
            $provider->update([
                'title' => $request->title,
                'key' => $request->key,
                'service' => $request->service,
            ]);

            return redirect()->route('admin.providers')->with('message', 'provider has been updated!');
        }else{
            return redirect()->back()->with('message', 'provider not found');
        }
        return redirect()->back()->with('message', 'There is a problem. Try again later');
    }

    public function activate(Provider $provider)
    {
        $provider = provider::find($provider->id);
        if ($provider) {
        
            $provider->update([
                'status' => 'Active',
            ]);
            return redirect()->route('admin.providers')->with('message', 'provider has been updated!');
            // return response()->json(['message' => 'provider record updated successfully'], 200);
        } else {
            return redirect()->back()->with('message', 'provider not found');
            // return response()->json(['message' => 'provider not found'], 404);
        }
        return redirect()->back()->with('message', 'There is an error. Try again');
    }

    public function deactivate(Provider $provider)
    {
        $provider = Provider::find($provider->id); // Assuming you have the $providerId variable
        if ($provider) {
            // Update the service's record in the services table
            $provider->update([
                'status' => 'Inactive',
            ]);
            return redirect()->route('admin.providers')->with('message', 'provider has been updated!');
            // return response()->json(['message' => 'provider record updated successfully'], 200);
        } else {
            return redirect()->back()->with('message', 'provider not found');
            // return response()->json(['message' => 'provider not found'], 404);
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
            $provider = Provider::find($request->id); // Assuming you have the $providerId variable
            if ($provider) {
                // Update the service's record in the services table
                $provider->update([
                    'status' => null,
                ]);
                return redirect()->route('admin.providers')->with('message', 'provider has been deleted!');
                // return response()->json(['message' => 'provider record updated successfully'], 200);
            } else {
                return redirect()->back()->with('message', 'provider not found');
                // return response()->json(['message' => 'provider not found'], 404);
            }
        } else {
            // return response()->json(['message' => 'Admin verification failed'], 401);
            return redirect()->back()->with('message', 'Admin Verification Failed');
        }
        
        return redirect()->back()->with('message', 'There is an error. Try again');
    }
}
