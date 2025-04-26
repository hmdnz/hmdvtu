<?php 

namespace App\Services\Transaction;

use App\Models\User;
use App\Models\Wallet;
use Exception;
use Illuminate\Support\Facades\Log;

class CheckWalletService
{
    protected $wallet;

    public function __construct($walletID = null, $userID = null)
    {
        // fetch the wallet using either walletID or userID
        $this->wallet = $this->findWallet($walletID, $userID);
    }

    // find the wallet by either wallet ID or user ID.
    private function findWallet($walletID, $userID)
    {
        if ($walletID) {
            return Wallet::where('id', $walletID)->first();
        }else{
            return Wallet::where('userID', $userID)->first();
        }

        return null;
    }

    /**
     * confirm if the wallet has sufficient funds.
     */
    public function confirmFunds(float $amount)
    {
        try {
            if (!$this->wallet) {
                return false;
            }

            if ($this->wallet->mainBalance >= $amount) {
                return $this->wallet;
            } else {
                return false;
            }
        } catch (Exception $exception) {
            Log::error("Error checking funds in user wallet: " . $exception->getMessage());
            return false;
        }
    }
}
