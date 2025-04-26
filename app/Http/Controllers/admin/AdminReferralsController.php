<?php

namespace App\Http\Controllers\admin;

use App\Models\Referrals;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminReferralsController extends Controller
{
    //
    public function index()
    {
        $referrals = Referrals::with(['user'])->whereNotNull('status')
                    ->orderBy('id', 'desc')->get();

        $activeReferrals = Referrals::where('status', 'Active')
        ->orderBy('id', 'desc')->get();

        $sattledReferrals = Referrals::where('status', 'Sattled')
        ->orderBy('id', 'desc')->get();

        // Count active referrals
        $countActiveReferrals = $activeReferrals->count();
        $countAllReferrals = $referrals->count();
        $countSattledReferrals = $sattledReferrals->count();
        // Get the sum of commissions from active referrals
        $sumOfCommissions = $activeReferrals->sum('commission');
        return view('admin.history.referrals', compact('referrals', 'countAllReferrals', 'countActiveReferrals', 'countSattledReferrals', 'sumOfCommissions'));
        // return view('user.referrals');
    }
}
