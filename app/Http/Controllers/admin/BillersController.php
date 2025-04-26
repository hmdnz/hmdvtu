<?php

namespace App\Http\Controllers\admin;

use App\Models\Admin;
use App\Models\Biller;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class BillersController extends Controller
{
    //
    
    public function index()
    {
        $billerCount = Biller::whereNotNull('status')->get()->count();
        $billers = Biller::whereNotNull('status')->orderBy('id', 'desc')->get();
        return view('admin.billers.all', [
            'billerCount' => $billerCount,
            'billers' => $billers,
        ]);
    }
    
    public function addBiller(Request $request)
    {
        // Validation logic here (you can use Laravel validation)
        $credentials = $request->validate([
            'title' => 'required',
            'service' => 'required',
            'status' => 'required',
            'variation' => 'required',
        ]);
        $credentials['adminID'] = auth()->user()->id; 
        // Create a new service
        $biller = Biller::create($credentials);
        // Redirect to the user dashboard
        return redirect()->route('admin.billers')->with('message', 'New biller has been added');
    }

    public function editBiller(Request $request)
    {
        $credentials = $request->validate([
            'title' => 'required',
            'service' => 'required',
            'id' => 'required',
            'variation' => 'required',
        ]);

        $biller = Biller::find($request->id); // Assuming you have the $userId variable
        if ($biller) {
            // Update the service's record in the services table
            $biller->update([
                'title' => $request->title,
                'service' => $request->service,
                'variation' => $request->variation,
            ]);
            return redirect()->route('admin.billers')->with('message', 'biller has been updated!');
        }else{
            return redirect()->back()->with('message', 'biller not found');
        }
        return redirect()->back()->with('message', 'There is a problem. Try again later');
    }

    public function activate(Biller $biller)
    {
        $biller = Biller::find($biller->id); // Assuming you have the $billerId variable
        if ($biller) {
            // Update the service's record in the services table
            $biller->update([
                'status' => 'Active',
            ]);
            return redirect()->route('admin.billers')->with('message', 'biller has been updated!');
            // return response()->json(['message' => 'biller record updated successfully'], 200);
        } else {
            return redirect()->back()->with('message', 'biller not found');
            // return response()->json(['message' => 'biller not found'], 404);
        }
        return redirect()->back()->with('message', 'There is an error. Try again');
    }

    public function deactivate(Biller $biller)
    {
        $biller = Biller::find($biller->id); // Assuming you have the $billerId variable
        if ($biller) {
            // Update the service's record in the services table
            $biller->update([
                'status' => 'Inactive',
            ]);
            return redirect()->route('admin.billers')->with('message', 'biller has been updated!');
            // return response()->json(['message' => 'biller record updated successfully'], 200);
        } else {
            return redirect()->back()->with('message', 'biller not found');
            // return response()->json(['message' => 'biller not found'], 404);
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
            $biller = Biller::find($request->id); // Assuming you have the $billerId variable
            if ($biller) {
                // Update the service's record in the services table
                $biller->update([
                    'status' => null,
                ]);
                return redirect()->route('admin.billers')->with('message', 'biller has been deleted!');
                // return response()->json(['message' => 'biller record updated successfully'], 200);
            } else {
                return redirect()->back()->with('message', 'biller not found');
                // return response()->json(['message' => 'biller not found'], 404);
            }
        } else {
            // return response()->json(['message' => 'Admin verification failed'], 401);
            return redirect()->back()->with('message', 'Admin Verification Failed');
        }
        
        return redirect()->back()->with('message', 'There is an error. Try again');
    }
}
