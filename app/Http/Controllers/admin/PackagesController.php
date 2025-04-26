<?php

namespace App\Http\Controllers\admin;

use App\Models\Admin;
use App\Models\Biller;
use App\Models\Package;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Provider;
use Illuminate\Support\Facades\Hash;

class PackagesController extends Controller
{
    //
    public function index()
    {
        $packageCount = Package::whereNotNull('status')->get()->count();
        // $packages = Package::with('biller')->get();
        $packages = Package::with('biller')
            ->whereNotNull('status')  // Add condition where status is not null
            ->orderBy('id', 'desc')  // Order by package_id in descending order
            ->get();

        return view('admin.packages.all', compact('packages', 'packageCount'));
    }

    public function showAddPackage()
    {
        $billers = Biller::whereNotNull('status')->get();
        $providers = Provider::whereNotNull('status')->orderBy('id', 'desc')->get();
        $services = Service::whereNotNull('status')->get();
        return view('admin.packages.add', [
            'billers' => $billers,
            'services' => $services,
            'providers' => $providers,
        ]);
    }

    public function showEditPackage($id)
    {
        $billers = Biller::whereNotNull('status')->get();
        $services = Service::whereNotNull('status')->get();
        $providers = Provider::whereNotNull('status')->orderBy('id', 'desc')->get();
        $package = Package::where('id',$id)
            ->whereNotNull('status')  
            ->orderBy('id', 'desc')  
            ->get()->first();
        return view('admin.packages.edit', compact('package', 'billers', 'services', 'providers'));
    }

    public function addPackage(Request $request)
    {
        $credentials = $request->validate([
            'title' => 'required',
            'service' => 'required',
            'provider' => 'required',
            'billerID' => 'nullable|integer',
            'type' => 'required',
            'planType' => 'nullable',
            'cost' => 'required',
            'price' => 'required',
            'size' => 'required',
            'validity' => 'required',
            'planID' => 'required',
        ]);
        $credentials['adminID'] = auth()->user()->id;
        $credentials['status'] = 'Active';
        // Create a new package
        $admin = Package::create($credentials);
        // Redirect to the package dashboard
        return redirect()->route('admin.packages')->with('message', 'New package has been added');
    }

    public function editPackage(Request $request)
    {
        $credentials = $request->validate([
            'id' => 'required',
            'billerID' => 'required',
            'title' => 'required',
            'service' => 'required',
            'provider' => 'required',
            'type' => 'required',
            'cost' => 'required',
            'price' => 'required',
            'size' => 'required',
            'validity' => 'required',
            'planType' => 'nullable',
            'planID' => 'required',
        ]);
        $package = Package::find($request->id); // Assuming you have the $userId variable
        if ($package) {
            
            // Update the package's record in the packages table
            $package->update([
                'billerID' => $request->billerID,
                'title' => $request->title,
                'service' => $request->service,
                'provider' => $request->provider,
                'type' => $request->type,
                'planType' => $request->planType,
                'cost' => $request->cost,
                'price' => $request->price,
                'size' => $request->size,
                'validity' => $request->validity,
                'planID' => $request->planID,
            ]);
            
            return redirect()->route('admin.packages')->with('message', 'package has been updated!');
        }else{
            return redirect()->back()->with('message', 'package not found');
        }
        return redirect()->back()->with('message', 'There is a problem. Try again later');
    }

    
    public function activate(Package $package)
    {
        $package = Package::find($package->id); // Assuming you have the $packageId variable
        if ($package) {
            // Update the package's record in the packages table
            $package->update([
                'status' => 'Active',
            ]);
            return redirect()->route('admin.packages')->with('message', 'Package has been updated!');
            // return response()->json(['message' => 'package record updated successfully'], 200);
        } else {
            return redirect()->back()->with('message', 'Package not found');
            // return response()->json(['message' => 'package not found'], 404);
        }
        return redirect()->back()->with('message', 'There is an error. Try again');
    }

    public function deactivate(Package $package)
    {
        $package = Package::find($package->id); // Assuming you have the $packageId variable
        if ($package) {
            // Update the package's record in the packages table
            $package->update([
                'status' => 'Inactive',
            ]);
            return redirect()->route('admin.packages')->with('message', 'package has been updated!');
            // return response()->json(['message' => 'package record updated successfully'], 200);
        } else {
            return redirect()->back()->with('message', 'package not found');
            // return response()->json(['message' => 'package not found'], 404);
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
            $package = Package::find($request->id); // Assuming you have the $packageId variable
            if ($package) {
                // Update the package's record in the packages table
                $package->update([
                    'status' => null,
                ]);
                return redirect()->route('admin.packages')->with('message', 'package has been deleted!');
                // return response()->json(['message' => 'package record updated successfully'], 200);
            } else {
                return redirect()->back()->with('message', 'package not found');
                // return response()->json(['message' => 'package not found'], 404);
            }
        } else {
            // return response()->json(['message' => 'Admin verification failed'], 401);
            return redirect()->back()->with('message', 'Admin Verification Failed');
        }
        
        return redirect()->back()->with('message', 'There is an error. Try again');
    }
}
