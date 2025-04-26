<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class NotifyController extends Controller
{
    //
    public function index()
    {

    }

    public function getToastrMessage()
    {
        // Retrieve Toastr message from the session
        $toastrMessage = session('toastr_message');

        // Clear Toastr message from the session
        session()->forget('toastr_message');

        return response()->json($toastrMessage);
    }

    
}
